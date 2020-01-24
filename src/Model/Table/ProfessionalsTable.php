<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Professionals Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CallsTable|\Cake\ORM\Association\HasMany $Calls
 * @property |\Cake\ORM\Association\HasMany $ChatMessages
 * @property \App\Model\Table\HighlightsTable|\Cake\ORM\Association\HasMany $Highlights
 * @property |\Cake\ORM\Association\HasMany $ProfessionalComments
 * @property \App\Model\Table\ProfessionalPhonesTable|\Cake\ORM\Association\HasMany $ProfessionalPhones
 * @property \App\Model\Table\ProfessionalServicesTable|\Cake\ORM\Association\HasMany $ProfessionalServices
 * @property \App\Model\Table\ProfessionalsAddressesTable|\Cake\ORM\Association\HasMany $ProfessionalsAddresses
 * @property \App\Model\Table\RatingsTable|\Cake\ORM\Association\HasMany $Ratings
 * @property |\Cake\ORM\Association\HasMany $Stories
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
        $this->hasMany('Calls', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('ChatMessages', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('Highlights', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('ProfessionalComments', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('ProfessionalPhones', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('ProfessionalServices', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('ProfessionalsAddresses', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('Ratings', [
            'foreignKey' => 'professional_id'
        ]);
        $this->hasMany('Stories', [
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
            ->allowEmptyString('description');

        $validator
            ->scalar('document')
            ->maxLength('document', 50)
            ->allowEmptyString('document');

        $validator
            ->date('date_birth')
            ->allowEmptyDate('date_birth');

        $validator
            ->scalar('photo')
            ->maxLength('photo', 255)
            ->allowEmptyString('photo');

        $validator
            ->scalar('backImage')
            ->maxLength('backImage', 255)
            ->allowEmptyString('backImage');

        $validator
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
