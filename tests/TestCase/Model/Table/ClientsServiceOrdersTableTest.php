<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClientsServiceOrdersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClientsServiceOrdersTable Test Case
 */
class ClientsServiceOrdersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ClientsServiceOrdersTable
     */
    public $ClientsServiceOrders;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ClientsServiceOrders',
        'app.Clients',
        'app.ClientsAddresses',
        'app.Services'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ClientsServiceOrders') ? [] : ['className' => ClientsServiceOrdersTable::class];
        $this->ClientsServiceOrders = TableRegistry::getTableLocator()->get('ClientsServiceOrders', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ClientsServiceOrders);

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
