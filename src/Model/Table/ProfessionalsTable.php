<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Professionals Model
 *
 * @property \App\Model\Table\CitiesTable|\Cake\ORM\Association\BelongsTo $Cities 
 * @property |\Cake\ORM\Association\BelongsTo $Subcategories
 * @property |\Cake\ORM\Association\BelongsTo $Users 
 * @property |\Cake\ORM\Association\HasMany $Highlights
 * @property \App\Model\Table\ProfessionalPhonesTable|\Cake\ORM\Association\HasMany $ProfessionalPhones
 * @property |\Cake\ORM\Association\HasMany $ProfessionalServices
 * @property |\Cake\ORM\Association\HasMany $ProfessionalsAddress
 * @property \App\Model\Table\RatingsTable|\Cake\ORM\Association\HasMany $Ratings
 *
 * @method \App\Model\Entity\Professional get($primaryKey, $options = [])
 * @method \App\Model\Entity\Professional newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Professional[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Professional|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Professional saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Professional patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Professional[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Professional findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProfessionalsTable extends Table
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

        $this->setTable('professionals');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Highlights', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('ProfessionalPhones', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('ProfessionalServices', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('ProfessionalsAddress', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('Ratings', [
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 255)
            ->requirePresence('phone', 'create')
            ->allowEmptyString('phone', false);

        $validator
            ->scalar('photo')
            ->maxLength('photo', 255)
            ->allowEmptyString('photo');

        $validator
            ->scalar('document')
            ->maxLength('document', 100)
            ->requirePresence('document', 'create')
            ->allowEmptyString('document', false);

        $validator
            ->date('date_birth')
            ->allowEmptyDate('date_birth')
            ->allowEmptyDate('date_birth', false);

        $validator
            ->boolean('active')
            ->allowEmptyString('active', false);

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
