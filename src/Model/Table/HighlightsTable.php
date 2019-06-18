<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Highlights Model
 *
 * @property \App\Model\Table\ProfessionalsTable|\Cake\ORM\Association\BelongsTo $Professionals
 *
 * @method \App\Model\Entity\Highlight get($primaryKey, $options = [])
 * @method \App\Model\Entity\Highlight newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Highlight[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Highlight|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Highlight saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Highlight patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Highlight[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Highlight findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HighlightsTable extends Table
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

        $this->setTable('highlights');

        $this->addBehavior('Timestamp');

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
            ->dateTime('finish')
            ->allowEmptyDateTime('finish');

        $validator
            ->integer('position')
            ->allowEmptyString('position');

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

        return $rules;
    }
}
