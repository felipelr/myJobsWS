<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfessionalsAddressesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProfessionalsAddressesTable Test Case
 */
class ProfessionalsAddressesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProfessionalsAddressesTable
     */
    public $ProfessionalsAddresses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProfessionalsAddresses',
        'app.Professionals',
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
        $config = TableRegistry::getTableLocator()->exists('ProfessionalsAddresses') ? [] : ['className' => ProfessionalsAddressesTable::class];
        $this->ProfessionalsAddresses = TableRegistry::getTableLocator()->get('ProfessionalsAddresses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProfessionalsAddresses);

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
