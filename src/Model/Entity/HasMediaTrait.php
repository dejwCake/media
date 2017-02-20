<?php
namespace DejwCake\Media\Model\Entity;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

trait HasMediaTrait
{
    /**
     * Media Table instance.
     *
     * @var \Cake\ORM\Table
     */
    protected $mediaTable;

    public function setMediaTable()
    {
        if(empty($this->mediaTable)) {
            $this->mediaTable = TableRegistry::get(Configure::read('Media.table'));
        }
    }

    /**
     * Determine if there is media in the given collection.
     *
     * @param $collectionName
     *
     * @return bool
     */
    public function hasMedia($collectionName = '')
    {
        return count($this->getMedia($collectionName)) ? true : false;
    }

    /**
     * Get media collection by its collectionName.
     *
     * @param string $collectionName
     * @param array $filters
     * @return mixed
     */
    public function getMedia($collectionName = '', $filters = [])
    {
        if(is_null($this->media)) {
            $this->setMediaTable();
            $query = $this->mediaTable->find('all')
                ->where([$this->mediaTable->alias().'.entity_id =' => $this->id])
                ->where([$this->mediaTable->alias().'.entity_class =' => get_class($this)]);
            if(!empty($collectionName)) {
                $query = $query->where([$this->mediaTable->alias().'.collection_name =' => $collectionName]);
            }
            return new Collection($query->all());
        } else {
            $col = (new Collection($this->media))->filter(function($item) use ($collectionName) {
                return $item->collection_name == $collectionName;
            });
            return $col;
        }
    }

    /**
     * Get the first media item of a media collection.
     *
     * @param string $collectionName
     * @param array  $filters
     *
     * @return bool|Media
     */
    public function getFirstMedia($collectionName = 'default', $filters = [])
    {
        $media = $this->getMedia($collectionName, $filters);

        return count($media) ? $media->first() : false;
    }

    /**
     * Get the url of the image for the given conversionName
     * for first media for the given collectionName.
     * If no profile is given, return the source's url.
     *
     * @param string $collectionName
     * @param string $conversionName
     *
     * @return string
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversionName = '')
    {
        $media = $this->getFirstMedia($collectionName);

        if (!$media) {
            return false;
        }

        return $media->getUrl($conversionName);
    }

    /**
     * Get the url of the image for the given conversionName
     * for first media for the given collectionName.
     * If no profile is given, return the source's url.
     *
     * @param string $collectionName
     * @param string $conversionName
     *
     * @return string
     */
    public function getFirstMediaPath($collectionName = 'default', $conversionName = '')
    {
        $media = $this->getFirstMedia($collectionName);

        if (!$media) {
            return false;
        }

        return $media->getPath($conversionName);
    }
}
