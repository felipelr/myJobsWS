<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ServiceSuggestions Model
 *
 * @property \App\Model\Table\SubcategoriesTable|\Cake\ORM\Association\BelongsTo $Subcategories
 * @property |\Cake\ORM\Association\BelongsTo $Professionals
 *
 * @method \App\Model\Entity\ServiceSuggestion get($primaryKey, $options = [])
 * @method \App\Model\Entity\ServiceSuggestion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ServiceSuggestion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceSuggestion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceSuggestion saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceSuggestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceSuggestion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceSuggestion findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServiceSuggestionsTable extends Table
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

        $this->setTable('service_suggestions');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Subcategories', [
            'foreignKey' => 'subcategory_id'
        ]);
        $this->belongsTo('Professionals', [
            'foreignKey' => 'professional_id'
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
        $validator
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 50)
            ->allowEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

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
        $rules->add($rules->existsIn(['subcategory_id'], 'Subcategories'));
        $rules->add($rules->existsIn(['professional_id'], 'Professionals'));

        return $rules;
    }
}
