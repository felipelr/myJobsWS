<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserAddressTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserAddressTable Test Case
 */
class UserAddressTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UserAddressTable
     */
    public $UserAddress;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UserAddress',
        'app.Users',
        'app.Cities'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UserAddress') ? [] : ['className' => UserAddressTable::class];
        $this->UserAddress = TableRegistry::getTableLocator()->get('UserAddress', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserAddress);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
