<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProfessionalServices Model
 *
 * @property \App\Model\Table\ProfessionalsTable|\Cake\ORM\Association\BelongsTo $Professionals
 * @property \App\Model\Table\ServicesTable|\Cake\ORM\Association\BelongsTo $Services
 *
 * @method \App\Model\Entity\ProfessionalService get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProfessionalService newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProfessionalService[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProfessionalService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProfessionalService saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProfessionalService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProfessionalService[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProfessionalService findOrCreate($search, callable $callback = null, $options = [])
 */
class ProfessionalServicesTable extends Table
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

        $this->setTable('professional_services');

        $this->belongsTo('Professionals', [
            'foreignKey' => 'professional_id'
        ]);
        $this->belongsTo('Services', [
            'foreignKey' => 'service_id'
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
            ->allowEmptyString('rating');

        $validator
            ->integer('amount_ratings')
            ->allowEmptyString('amount_ratings');

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
        $rules->add($rules->existsIn(['service_id'], 'Services'));

        return $rules;
    }
}
