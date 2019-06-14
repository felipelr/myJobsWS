<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProfessionalPhones Model
 *
 * @property \App\Model\Table\ProfessionalsTable|\Cake\ORM\Association\BelongsTo $Professionals
 *
 * @method \App\Model\Entity\ProfessionalPhone get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProfessionalPhone newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProfessionalPhone[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProfessionalPhone|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProfessionalPhone saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProfessionalPhone patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProfessionalPhone[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProfessionalPhone findOrCreate($search, callable $callback = null, $options = [])
 */
class ProfessionalPhonesTable extends Table
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

        $this->setTable('professional_phones');

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
            ->allowEmptyString('phone')
            ->add('phone', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('phone_string')
            ->maxLength('phone_string', 50)
            ->allowEmptyString('phone_string');

        $validator
            ->scalar('description')
            ->maxLength('description', 100)
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
        $rules->add($rules->isUnique(['phone']));
        $rules->add($rules->existsIn(['professional_id'], 'Professionals'));

        return $rules;
    }
}
