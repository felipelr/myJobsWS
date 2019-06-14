<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProfessionalPhonesFixture
 */
class ProfessionalPhonesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'phone' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'professional_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'phone_string' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'professional_id_phone_fk' => ['type' => 'index', 'columns' => ['professional_id'], 'length' => []],
        ],
        '_constraints' => [
            'phone' => ['type' => 'unique', 'columns' => ['phone'], 'length' => []],
            'professional_id_phone_fk' => ['type' => 'foreign', 'columns' => ['professional_id'], 'references' => ['professionals', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'phone' => 1,
                'professional_id' => 1,
                'phone_string' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
