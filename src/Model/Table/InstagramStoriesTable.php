<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InstagramStories Model
 *
 * @property \App\Model\Table\ProfessionalsTable|\Cake\ORM\Association\BelongsTo $Professionals
 *
 * @method \App\Model\Entity\InstagramStory get($primaryKey, $options = [])
 * @method \App\Model\Entity\InstagramStory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InstagramStory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InstagramStory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InstagramStory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InstagramStory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InstagramStory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InstagramStory findOrCreate($search, callable $callback = null, $options = [])
 */
class InstagramStoriesTable extends Table
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

        $this->setTable('instagram_stories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->scalar('json')
            ->allowEmptyString('json');

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
