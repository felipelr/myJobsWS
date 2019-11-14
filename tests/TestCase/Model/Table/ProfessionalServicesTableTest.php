<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfessionalServicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProfessionalServicesTable Test Case
 */
class ProfessionalServicesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProfessionalServicesTable
     */
    public $ProfessionalServices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProfessionalServices',
        'app.Professionals',
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
        $config = TableRegistry::getTableLocator()->exists('ProfessionalServices') ? [] : ['className' => ProfessionalServicesTable::class];
        $this->ProfessionalServices = TableRegistry::getTableLocator()->get('ProfessionalServices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProfessionalServices);

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
