<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserAddressFixture
 */
class UserAddressFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'user_address';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'user_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'city_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'street' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'street_number' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'neighborhood' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'latitude' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'longitude' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'user_id_addres_fk' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'cities_addres_fk' => ['type' => 'index', 'columns' => ['city_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'cities_addres_fk' => ['type' => 'foreign', 'columns' => ['city_id'], 'references' => ['cities', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'user_id_addres_fk' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'user_id' => 1,
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
