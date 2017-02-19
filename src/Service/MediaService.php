<?php namespace DejwCake\Media\Service;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use DejwCake\Media\Exception\FileCannotBeImportedException;
use DejwCake\Media\Exception\FileDoesNotExistException;
use DejwCake\Media\Exception\FilesystemDoesNotExistsException;
use DejwCake\Media\Exception\FileTooBigException;
use DejwCake\Media\Helpers\File;
use Cake\Filesystem\File as SystemFile;
use DejwCake\Media\Helpers\UploadedFile;
use DejwCake\Media\Model\Entity\Media;

class MediaService
{
    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * @var Table
     */
    protected $table;

    /**
     * @var bool
     */
    protected $preserveOriginal = false;

    /**
     * @var string|UploadedFile|File
     */
    protected $file;

    /**
     * @var string
     */
    protected $pathToFile;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $mediaName;

    /**
     * @var MediaFileService
     */
    protected $mediaFileService;
    /**
     * @var
     */
    private $entityTable;

    /**
     * FileToMediaService constructor.
     *
     * @param EntityInterface $entity
     * @param Table $entityTable
     * @param Table $table
     */
    public function __construct(EntityInterface $entity, Table $entityTable, Table $table)
    {
        $this->mediaFileService = new MediaFileService();
        $this->entity = $entity;
        $this->entityTable = $entityTable;
        $this->table = $table;

        return $this;
    }

    /**
     * Set the file that needs to be imported.
     *
     * @param string|UploadedFile|File $file
     * @return $this
     *
     * @throws FileCannotBeImportedException
     */
    public function setFile($file)
    {
        $this->file = $file;

        if (is_string($file)) {
            $this->pathToFile = $file;
            $this->setFileName(pathinfo($file, PATHINFO_BASENAME));
            $this->mediaName = pathinfo($file, PATHINFO_FILENAME);
            return $this;
        }

        if ($file instanceof UploadedFile) {
            $this->pathToFile = $file->getPath().'/'.$file->getFilename();
            $this->setFileName($file->getClientOriginalName());
            $this->mediaName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            return $this;
        }

        if ($file instanceof File) {
            $this->pathToFile = $file->getPath().'/'.$file->getFilename();
            $this->setFileName(pathinfo($file->getFilename(), PATHINFO_BASENAME));
            $this->mediaName = pathinfo($file->getFilename(), PATHINFO_FILENAME);

            return $this;
        }

        throw new FileCannotBeImportedException('Only strings, FileObjects and UploadedFileObjects can be imported');
    }

    /**
     * Set the name of the file that is stored on disk.
     *
     * @param $fileName
     *
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $this->sanitizeFileName($fileName);
        return $this;
    }

    /**
     * Set the collection name where to import the file.
     * Will also start the import process.
     *
     * @param string $collectionName
     * @param string $diskName
     * @return Media
     *
     * @throws FileDoesNotExistException
     * @throws FileTooBigException
     */
    public function toCollection($collectionName = 'default', $diskName = '')
    {
        return $this->toCollectionOnDisk($collectionName, $diskName);
    }

    /**
     * @param string $collectionName
     * @param string $diskName
     *
     * @return Media
     *
     * @throws FileDoesNotExistException
     * @throws FileTooBigException
     * @throws FilesystemDoesNotExistsException
     */
    public function toCollectionOnDisk($collectionName = 'default', $diskName = '')
    {
        if (!is_file($this->pathToFile)) {
            throw new FileDoesNotExistException();
        }

        if (filesize($this->pathToFile) > Configure::read('Media.maxFileSize')) {
            throw new FileTooBigException();
        }

        //Create media entity object and save it to db
        $mediaEntity = $this->table->newEntity();
        $mediaData = [
            'entity_id' => $this->entity->id,
            'entity_class' => get_class($this->entity),
            'title' => $this->mediaName,
            'file_name' => $this->fileName,
            'collection_name' => $collectionName,
            'size' => filesize($this->pathToFile),
            'mime_type' => (new SystemFile($this->pathToFile))->mime(),
            'manipulations' => '',
            'properties' => '',
            'disk' => $this->determineDiskName($diskName),
            'sort' => 0,
        ];
        $media = $this->table->patchEntity($mediaEntity, $mediaData, [
            'validate' => true,
        ]);
        //TODO fix title translation
        if ($this->table->hasBehavior('Translate')) {
            $fields = $this->table->behaviors()->get('Translate')->config('fields');
            foreach (Configure::read('App.supportedLanguages') as $language => $languageSettings) {
                $translation[$languageSettings['locale']] = $this->table->newEntity();
                foreach ($fields as $field) {
                    $translatedValue = $media->get($field);
                    $translation[$languageSettings['locale']][$field] = $translatedValue;
                }
            }
            $media->set('_translations', $translation);
        }
        if ($this->table->save($media)) {
            //Save file to dir and make conversions
            $conversions = $this->entityTable->getConversions($media->get('collection_name'));
            $this->mediaFileService->add($this->pathToFile, $media, $this->fileName, $conversions);

            if (!$this->preserveOriginal) {
                unlink($this->pathToFile);
            }
        }

        return $media;
    }

    /**
     * Determine the disk to be used.
     *
     * @param string $diskName
     *
     * @return string
     *
     * @throws FilesystemDoesNotExistsException
     */
    protected function determineDiskName($diskName)
    {
        if ($diskName == '') {
            $diskName = Configure::read('Media.defaultFilesystem');
        }

        if (is_null(Configure::read("Media.disks.{$diskName}"))) {
            throw new FilesystemDoesNotExistsException("There is no filesystem named {$diskName}");
        }

        return $diskName;
    }

    /**
     * When adding the file to the media library, the original file
     * will be preserved.
     *
     * @return $this
     */
    public function preservingOriginal()
    {
        $this->preserveOriginal = true;

        return $this;
    }

    /**
     * Sanitize the given file name.
     *
     * @param $fileName
     *
     * @return string
     */
    protected function sanitizeFileName($fileName)
    {
        return str_replace(['#', '/', '\\'], '-', $fileName);
    }
}