<?php namespace DejwCake\Media\Service;

use Cake\Core\Configure;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use DejwCake\Media\Helpers\PathGenerator;
use DejwCake\Media\Model\Entity\Media;

class MediaFileService
{

    /**
     * Add a file to the mediaLibrary for the given media.
     *
     * @param string $file
     * @param Media $media
     * @param string $targetFileName
     * @internal param Media $mediaEntity
     */
    public function add($file, Media $media, $targetFileName = '', $conversions = null)
    {
        //save file to dir
        $this->copyToMediaLibrary($file, $media, false, $targetFileName);

        //make conversions
        if($conversions) {
            (new MediaFileConversionService($file, $media, $conversions, $this->getMediaDirectory($media, true)))
                ->createConversionFiles();
        }
    }

    /**
     * Copy a file to the mediaLibrary for the given $media.
     *
     * @param string                     $file
     * @param Media $media
     * @param bool                       $conversions
     * @param string                     $targetFileName
     */
    public function copyToMediaLibrary($file, Media $media, $conversions = false, $targetFileName = '')
    {
        $destination = $this->getMediaDirectory($media, $conversions)->pwd().
            ($targetFileName == '' ? pathinfo($file, PATHINFO_BASENAME) : $targetFileName);
        $file = new File($file);
        $file->copy($destination, true);
    }

    /**
     * Return the directory where all files of the given media are stored.
     *
     * @param Media $media
     * @param bool $conversion
     * @return string
     */
    public function getMediaDirectory(Media $media, $conversion = false)
    {
        $directory = $conversion ?
            (new PathGenerator())->getPathForConversions($media) :
            (new PathGenerator())->getPath($media);

        return $this->makeDirectory($media->disk, $directory);
    }

    /**
     * @param $diskName
     * @param $directory
     * @return Folder
     */
    protected function makeDirectory($diskName, $directory) {
        $root = Configure::read('Media.disks.'.$diskName.'.root');
        return new Folder($root . DS . $directory, true, 0755);
    }

}