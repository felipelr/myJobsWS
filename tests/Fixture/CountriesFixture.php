<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CountriesFixture
 */
class CountriesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'description' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description_pt' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'initials' => ['type' => 'string', 'length' => 5, 'null' => false, 'default' => '0', 'collate' => 'latin1_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'code' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
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
                'description' => 'Lorem ipsum dolor sit amet',
                'description_pt' => 'Lorem ipsum dolor sit amet',
                'initials' => 'Lor',
                'code' => 1
            ],
        ];
        parent::init();
    }
}
