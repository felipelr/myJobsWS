<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChatMessages Model
 *
 * @property \App\Model\Table\ProfessionalsTable|\Cake\ORM\Association\BelongsTo $Professionals
 * @property \App\Model\Table\ClientsTable|\Cake\ORM\Association\BelongsTo $Clients
 *
 * @method \App\Model\Entity\ChatMessage get($primaryKey, $options = [])
 * @method \App\Model\Entity\ChatMessage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ChatMessage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ChatMessage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChatMessage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChatMessage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ChatMessage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ChatMessage findOrCreate($search, callable $callback = null, $options = [])
 */
class ChatMessagesTable extends Table
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

        $this->setTable('chat_messages');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Professionals', [
            'foreignKey' => 'professional_id'
        ]);
        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id'
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
            ->date('date')
            ->allowEmptyDate('date');

        $validator
            ->time('time')
            ->allowEmptyTime('time');

        $validator
            ->scalar('message')
            ->allowEmptyString('message');

        $validator
            ->scalar('from')
            ->maxLength('from', 50)
            ->allowEmptyString('from');

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
        $rules->add($rules->existsIn(['client_id'], 'Clients'));

        return $rules;
    }
}
