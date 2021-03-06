<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\CallsTable|\Cake\ORM\Association\HasMany $Calls
 * @property \App\Model\Table\ClientsTable|\Cake\ORM\Association\HasMany $Clients
 * @property \App\Model\Table\ProfessionalsTable|\Cake\ORM\Association\HasMany $Professionals
 * @property \App\Model\Table\RatingsTable|\Cake\ORM\Association\HasMany $Ratings
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Calls', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Clients', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Professionals', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Ratings', [
            'foreignKey' => 'user_id'
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
            ->email('email')
            ->allowEmptyString('email', false);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->allowEmptyString('password', false);

        $validator
            ->scalar('facebook_token')
            ->maxLength('facebook_token', 255)
            ->allowEmptyString('facebook_token', false);

        $validator
            ->scalar('google_token')
            ->maxLength('google_token', 255)
            ->allowEmptyString('google_token', false);

        $validator
            ->scalar('fcm_token')
            ->maxLength('fcm_token', 255)
            ->allowEmptyString('fcm_token', false);

        $validator
            ->boolean('active')
            ->allowEmptyString('active');

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));

        return $rules;
    }
}
