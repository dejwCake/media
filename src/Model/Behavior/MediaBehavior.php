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
        $persistedEntity = $this->getPersistedEntityWithMedia($entity);
        $mediaData = $entity->medium;
        $mediaCollections = $this->_table->getMediaCollections();
        foreach($mediaCollections as $mediaCollectionName => $mediaCollection) {
            if(empty($mediaData[$mediaCollectionName]['file'])) {
                if(!empty($mediaData[$mediaCollectionName]['hasDeleted']) ) {
                    $existingMedia = $this->getMedia($persistedEntity, $mediaCollectionName);
                    foreach($existingMedia as $existingFile) {
                        $this->mediaTable->delete($existingFile);
                    }
                }
                continue;
            }
            $filePath = $mediaData[$mediaCollectionName]['file'];
            if(is_file($filePath)) {
                $this->clearMediaCollection($persistedEntity, $mediaCollectionName);
                $this->addMediaToCollection($persistedEntity, $filePath, $mediaCollectionName);
            }
        }

        //TODO process gallery
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
            ->firstOrFail();
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
