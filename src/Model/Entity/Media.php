<?php
namespace DejwCake\Media\Model\Entity;

use Cake\Core\Configure;
use Cake\Filesystem\File;
use Cake\I18n\Number;
use Cake\ORM\Entity;
use Cake\ORM\Behavior\Translate\TranslateTrait;
use DejwCake\Media\Helpers\PathGenerator;

/**
 * Media Entity
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_class
 * @property string $title
 * @property string $file_name
 * @property string $collection_name
 * @property int $size
 * @property string $mime_type
 * @property string $manipulations
 * @property string $properties
 * @property string $disk
 * @property int $sort
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $deleted
 *
 * @property \DejwCake\Media\Model\Entity\Entity $entity
 * @property \App\Model\Entity\User $user
 */
class Media extends Entity
{
    use TranslateTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    protected $_virtual = [
        'type_from_extension',
        'type_from_mime',
        'type',
        'extension',
        'human_readable_size',
    ];

    const TYPE_OTHER = 'other';
    const TYPE_IMAGE = 'image';
    const TYPE_PDF = 'pdf';

    /**
     * Determine the type of a file.
     *
     * @return string
     */
    protected function _getType()
    {
        $type = $this->type_from_extension;
        if ($type !== Media::TYPE_OTHER) {
            return $type;
        }

        return $this->type_from_mime;
    }

    /**
     * Determine the type of a file from its file extension
     *
     * @return string
     */
    protected function _getTypeFromExtension()
    {
        $extension = strtolower($this->extension);

        if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
            return static::TYPE_IMAGE;
        }

        if ($extension == 'pdf') {
            return static::TYPE_PDF;
        }

        return static::TYPE_OTHER;
    }

    /**
     * Determine the type of a file from its mime type
     *
     * @return string
     */
    protected function _getTypeFromMime()
    {
        if ($this->getDiskDriverName() != 'local') {
            return static::TYPE_OTHER;
        }

        $mime = (new File($this->getPath()))->mime();

        if (in_array($mime, ['image/jpeg', 'image/gif', 'image/png'])) {
            return static::TYPE_IMAGE;
        }

        if ($mime == 'application/pdf') {
            return static::TYPE_PDF;
        }

        return static::TYPE_OTHER;
    }

    /**
     * @return string
     */
    protected function _getExtension()
    {
        return pathinfo($this->_properties['file_name'], PATHINFO_EXTENSION);
    }

    /**
     * @return string
     */
    protected function _getHumanReadableSize()
    {
        return Number::toReadableSize($this->_properties['size']);
    }

    /**
     * @return string
     */
    public function getDiskDriverName()
    {
        return Configure::read('Media.disks.'.$this->_properties['disk'].'.driver');
    }

    /**
     * Get the original Url to a media-file.
     *
     * @param string $conversionName
     * @return string
     */
    public function getUrl($conversionName = '')
    {
        return str_replace(WWW_ROOT, "/", Configure::read('Media.disks.'.$this->_properties['disk'].'.root') . DS . $this->getPathRelativeToRoot($conversionName));
    }

    /**
     * Get the original path to a media-file.
     *
     * @param string $conversionName
     * @return string
     */
    public function getPath($conversionName = '')
    {
        return realpath(Configure::read('Media.disks.'.$this->_properties['disk'].'.root')) . DS . $this->getPathRelativeToRoot($conversionName);
    }

    /**
     * Get the path to the requested file relative to the root of the media directory.
     *
     * @return string
     */
    public function getPathRelativeToRoot($conversionName = '')
    {
        if (empty($conversionName)) {
            return (new PathGenerator())->getPath($this).$this->_properties['file_name'];
        }

        return (new PathGenerator())->getPathForConversions($this).$conversionName.'.'.$this->extension;
    }
}
