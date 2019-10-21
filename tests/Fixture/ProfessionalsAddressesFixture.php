<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProfessionalsAddressesFixture
 */
class ProfessionalsAddressesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'professional_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'city_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'street' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'street_number' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'neighborhood' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'latitude' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'longitude' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'professionals_address_id_key' => ['type' => 'index', 'columns' => ['professional_id'], 'length' => []],
            'cities_professional_address_id_key' => ['type' => 'index', 'columns' => ['city_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'cities_professional_address_id_key' => ['type' => 'foreign', 'columns' => ['city_id'], 'references' => ['cities', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'professionals_address_id_key' => ['type' => 'foreign', 'columns' => ['professional_id'], 'references' => ['professionals', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'id' => 1,
                'professional_id' => 1,
                'city_id' => 1,
                'street' => 'Lorem ipsum dolor sit amet',
                'street_number' => 'Lorem ipsum dolor sit amet',
                'neighborhood' => 'Lorem ipsum dolor sit amet',
                'latitude' => 'Lorem ipsum dolor sit amet',
                'longitude' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
