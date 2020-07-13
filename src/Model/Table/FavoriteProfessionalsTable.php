<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FavoriteProfessionals Model
 *
 * @property \App\Model\Table\ProfessionalsTable|\Cake\ORM\Association\BelongsTo $Professionals
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\FavoriteProfessional get($primaryKey, $options = [])
 * @method \App\Model\Entity\FavoriteProfessional newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FavoriteProfessional[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FavoriteProfessional|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FavoriteProfessional saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FavoriteProfessional patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FavoriteProfessional[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FavoriteProfessional findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FavoriteProfessionalsTable extends Table
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

        $this->setTable('favorite_professionals');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Professionals', [
            'foreignKey' => 'professional_id'
        ]);
        $this->belongsTo('Users', [
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
