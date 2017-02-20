<?php
namespace DejwCake\Media\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Media Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Entities
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \DejwCake\Media\Model\Entity\Media get($primaryKey, $options = [])
 * @method \DejwCake\Media\Model\Entity\Media newEntity($data = null, array $options = [])
 * @method \DejwCake\Media\Model\Entity\Media[] newEntities(array $data, array $options = [])
 * @method \DejwCake\Media\Model\Entity\Media|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \DejwCake\Media\Model\Entity\Media patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \DejwCake\Media\Model\Entity\Media[] patchEntities($entities, array $data, array $options = [])
 * @method \DejwCake\Media\Model\Entity\Media findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\Muffin/Trash.TrashBehavior
 * @mixin \Cake\ORM\Behavior\DejwCake/Helpers.SortableBehavior
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 */
class MediaTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('media');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Muffin/Trash.Trash');
        $this->addBehavior('DejwCake/Helpers.Sortable');
        $this->addBehavior('Translate', ['fields' => ['title'], 'translationTable' => 'MediaI18n']);
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

        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('file_name', 'create')
            ->notEmpty('file_name');

        $validator
            ->requirePresence('collection_name', 'create')
            ->notEmpty('collection_name');

        $validator
            ->integer('size')
            ->requirePresence('size', 'create')
            ->notEmpty('size');

        $validator
            ->requirePresence('mime_type', 'create')
            ->notEmpty('mime_type');

        $validator
            ->requirePresence('disk', 'create')
            ->notEmpty('disk');

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
        return $rules;
    }
}
