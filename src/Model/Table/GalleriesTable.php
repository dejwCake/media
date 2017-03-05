<?php
namespace DejwCake\Media\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Galleries Model
 *
 * @method \DejwCake\Media\Model\Entity\Gallery get($primaryKey, $options = [])
 * @method \DejwCake\Media\Model\Entity\Gallery newEntity($data = null, array $options = [])
 * @method \DejwCake\Media\Model\Entity\Gallery[] newEntities(array $data, array $options = [])
 * @method \DejwCake\Media\Model\Entity\Gallery|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \DejwCake\Media\Model\Entity\Gallery patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \DejwCake\Media\Model\Entity\Gallery[] patchEntities($entities, array $data, array $options = [])
 * @method \DejwCake\Media\Model\Entity\Gallery findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\Muffin/Trash.TrashBehavior
 * @mixin \Cake\ORM\Behavior\DejwCake/Helpers.SluggableBehavior
 * @mixin \Cake\ORM\Behavior\DejwCake/Helpers.SortableBehavior
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 */
class GalleriesTable extends Table
{
    use HasMediaTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('galleries');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Muffin/Trash.Trash');
        $this->addBehavior('DejwCake/Helpers.Sluggable');
        $this->addBehavior('DejwCake/Helpers.Sortable');
        $this->addBehavior('Translate', ['fields' => ['title', 'slug', 'text'], 'translationTable' => 'GalleriesI18n']);
        $this->addBehavior('DejwCake/Media.Media');

        $this->hasMany('Media', [
            'className' => 'DejwCake/Media.Media',
            'foreignKey' => 'entity_id',
            'conditions' => ['Media.entity_class' => 'DejwCake\\Media\\Model\\Entity\\Gallery'],
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $translationValidator = new Validator();
        $translationValidator
            ->requirePresence('title', 'create')
            ->allowEmpty('title');
        $translationValidator
            ->requirePresence('text', 'create')
            ->allowEmpty('text');

        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->allowEmpty('text');

        $validator
            ->allowEmpty('enabled_in_locales');

        $validator
            ->addNestedMany('_translations', $translationValidator)
            ->requirePresence('_translations', 'false')
            ->allowEmpty('_translations');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['title', 'deleted'], ['allowMultipleNulls' => false, 'message' => __d('media', 'This value is not unique')]));
        $rules->add($rules->isUnique(['slug', 'deleted'], ['allowMultipleNulls' => false, 'message' => __d('media', 'This value is not unique')]));

        return $rules;
    }

    /**
     * @param Schema $schema
     * @return Schema
     */
    protected function _initializeSchema(Schema $schema)
    {
        $schema->columnType('enabled_in_locales', 'json');
        return $schema;
    }

    /* ************************ MEDIA ************************ */

    /**
     * Media collections
     *
     * @return array
     */
    public function getMediaCollections() {
        return [
            'cover' => [
                'title' => __d('media', 'Cover'),
                'type' => 'image',
                'template' => 'imageTemplate',
                'multiple' => false,
                'conversions' => [
                    'original' => [
                        'name' => 'Original',
                    ],
                    'thumb' => [
                        'name' => 'Thumb',
                        'width' => '215',
                        'height' => '130',
                    ],
                    'thumbRetina' => [
                        'name' => 'Thumb Retina',
                        'width' => '430',
                        'height' => '260',
                    ],
                ],
            ],
            'images' => [
                'title' => __d('media', 'Images'),
                'type' => 'gallery',
                'template' => 'imageTemplate',
                'multiple' => true,
                'conversions' => [
                    'original' => [
                        'name' => 'Original',
                    ],
                    'thumb' => [
                        'name' => 'Thumb',
                        'width' => '215',
                        'height' => '130',
                    ],
                    'thumbRetina' => [
                        'name' => 'Thumb Retina',
                        'width' => '430',
                        'height' => '260',
                    ],
                ],
            ],
        ];
    }
}
