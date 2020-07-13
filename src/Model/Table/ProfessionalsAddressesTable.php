<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProfessionalsAddresses Model
 *
 * @property \App\Model\Table\ProfessionalsTable|\Cake\ORM\Association\BelongsTo $Professionals
 * @property \App\Model\Table\CitiesTable|\Cake\ORM\Association\BelongsTo $Cities
 *
 * @method \App\Model\Entity\ProfessionalsAddress get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProfessionalsAddress newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProfessionalsAddress[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProfessionalsAddress|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProfessionalsAddress saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProfessionalsAddress patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProfessionalsAddress[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProfessionalsAddress findOrCreate($search, callable $callback = null, $options = [])
 */
class ProfessionalsAddressesTable extends Table
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

        $this->setTable('professionals_addresses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Professionals', [
            'foreignKey' => 'professional_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id',
            'joinType' => 'INNER'
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
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('street')
            ->maxLength('street', 255)
            ->allowEmptyString('street', false);

        $validator
            ->scalar('street_number')
            ->maxLength('street_number', 100)
            ->allowEmptyString('street_number', false);

        $validator
            ->scalar('neighborhood')
            ->maxLength('neighborhood', 255)
            ->allowEmptyString('neighborhood', false);

        $validator
            ->scalar('complement')
            ->maxLength('complement', 255)
            ->allowEmptyString('complement', false);

        $validator
            ->scalar('latitude')
            ->maxLength('latitude', 255)
            ->allowEmptyString('latitude', false);

        $validator
            ->scalar('longitude')
            ->maxLength('longitude', 255)
            ->allowEmptyString('longitude', false);

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
        $rules->add($rules->existsIn(['professional_id'], 'Professionals'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));

        return $rules;
    }
}
