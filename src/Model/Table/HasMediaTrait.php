<?php
namespace DejwCake\Media\Model\Table;

use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;

trait HasMediaTrait
{
    public function getImageCollections()
    {
        $collections = [];
        foreach ($this->getMediaCollections() as $key => $collection) {
            if($collection['type'] == 'image' ) {
                $collections[$key] = $collection;
            }
        }
        return $collections;
    }

    public function getGalleryCollections()
    {
        $collections = [];
        foreach ($this->getMediaCollections() as $key => $collection) {
            if($collection['type'] == 'gallery' ) {
                $collections[$key] = $collection;
            }
        }
        return $collections;
    }

    public function getFileCollections()
    {
        $collections = [];
        foreach ($this->getMediaCollections() as $key => $collection) {
            if($collection['type'] == 'file' ) {
                $collections[$key] = $collection;
            }
        }
        return $collections;
    }

    public function getVideoCollections()
    {
        $collections = [];
        foreach ($this->getMediaCollections() as $key => $collection) {
            if($collection['type'] == 'video' ) {
                $collections[$key] = $collection;
            }
        }
        return $collections;
    }

    /**
     * Media conversions
     *
     * @return array
     */
    public function getConversions($collectionName = null) {
        $conversions = [];
        foreach ($this->getMediaCollections() as $key => $collection) {
            if(!empty($collection['conversions'])) {
                if(!empty($collectionName) && $collectionName === $key ) {
                    $conversions = $collection['conversions'];
                } else if (empty($collectionName)){
                    array_merge($conversions, $collection['conversions']);
                }
            }
        }
        return $conversions;
    }
}
