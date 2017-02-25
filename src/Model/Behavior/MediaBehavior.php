<?php
namespace DejwCake\Media\Model\Behavior;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use DejwCake\Media\Service\FileToMediaService;
use DejwCake\Media\Service\MediaService;

class MediaBehavior extends Behavior
{
    /**
     * Table instance.
     *
     * @var \Cake\ORM\Table
     */
    protected $_table;

    /**
     * Media Table instance.
     *
     * @var \Cake\ORM\Table
     */
    protected $mediaTable;

    protected $_defaultConfig = [
        'implementedMethods' => [
            'getCollection' => 'getCollection',
        ]
    ];

    public function initialize(array $config)
    {
        $this->mediaTable = TableRegistry::get(Configure::read('Media.table'));
    }

    public function slug(Entity $entity)
    {

    }

    public function beforeSave(Event $event, EntityInterface $entity)
    {
    }

    public function afterSave(Event $event, EntityInterface $entity)
    {
        //get persisted entity
        $persistedEntity = $this->getPersistedEntityWithMedia($entity);

        //we have soft deleted entity, soft deleting also media
        if(empty($persistedEntity) && !empty($entity->deleted) && $entity->dirty('deleted')) {
            foreach ($entity->getMedia('', [], true) as $medium) {
                $medium->delete();
            }
            return true;
        }

        //get sent media data
        $mediaData = $entity->medium;
        //get collections and iterate over them
        $mediaCollections = $this->_table->getMediaCollections();
        foreach($mediaCollections as $mediaCollectionName => $mediaCollection) {
            if(!empty($mediaData[$mediaCollectionName]) && is_array($mediaData[$mediaCollectionName])) {
                //if have data for collection and is is an array

                if($mediaCollection['multiple'] || $mediaCollection['type'] == 'gallery') {
                    //process gallery or multiple item collection
                    //get media id for delete
                    $existingMediaForDelete = (new Collection($persistedEntity->getMedia($mediaCollectionName)))->indexBy('id')->toArray();
                    foreach ($mediaData[$mediaCollectionName] as $medium) {
                        if(!empty($medium['id'])) {
                            //if id is present, remove from for delete based on deleted flag
                            if(empty($medium['deleted'])) {
                                //remove from existingMediaForDelete
                                unset($existingMediaForDelete[$medium['id']]);
                            }
                        } else {
                            //if id is not present add file
                            if (!empty($medium['file']) && empty($medium['deleted'])){
                                //add file to entity
                                if(is_file($medium['file'])) {
                                    $this->addMediaToCollection($persistedEntity, $medium['file'], $mediaCollectionName);
                                }
                            }
                        }
                    }
                    //delete existing media for delete
                    foreach ($existingMediaForDelete as $existingMediumForDelete) {
                        $this->mediaTable->delete($existingMediumForDelete);
                    }
                } else {
                    $medium = array_shift($mediaData[$mediaCollectionName]);

                    if(empty($medium['file'])) {
                        if(!empty($medium['deleted']) ) {
                            $this->clearMediaCollection($persistedEntity, $mediaCollectionName);
                        }
                        continue;
                    }
                    if(is_file($medium['file'])) {
                        $this->clearMediaCollection($persistedEntity, $mediaCollectionName);
                        $this->addMediaToCollection($persistedEntity, $medium['file'], $mediaCollectionName);
                    }
                }
            } else {
                //if no data for collection, clear it
                $this->clearMediaCollection($persistedEntity, $mediaCollectionName);
            }
        }
    }

    protected function addMediaToCollection(EntityInterface $entity, $filePath, $collectionName) {
        (new MediaService($entity, $this->_table, $this->mediaTable))
            ->setFile($filePath)
            ->toCollection($collectionName);
    }

    public function findMedia(Query $query, array $options)
    {
        return $query->contain(['Media' => ['finder' => 'all']]);
    }

    protected function getPersistedEntityWithMedia(EntityInterface $entity) {
        $persistedEntity = $this->_table
            ->find('media')
            ->where([$this->_table->alias().'.id' => $entity->id])
            ->first();
        return $persistedEntity;
    }

    protected function getMedia(EntityInterface $entity, $collectionName) {
        return (new Collection($entity->media))->filter(function ($item) use ($collectionName) {
            return $item->collection_name == $collectionName;
        });
    }

    protected function clearMediaCollection(EntityInterface $entity, $collectionName) {
        $mediaToDelete = $this->getMedia($entity, $collectionName);
        $mediaToDelete->each(function($media) {
            $this->mediaTable->delete($media);
        });
    }
}
