<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClientsAddressTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClientsAddressTable Test Case
 */
class ClientsAddressTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ClientsAddressTable
     */
    public $ClientsAddress;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ClientsAddress',
        'app.Clients',
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
        $config = TableRegistry::getTableLocator()->exists('ClientsAddress') ? [] : ['className' => ClientsAddressTable::class];
        $this->ClientsAddress = TableRegistry::getTableLocator()->get('ClientsAddress', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ClientsAddress);

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
