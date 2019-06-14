<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfessionalsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProfessionalsTable Test Case
 */
class ProfessionalsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProfessionalsTable
     */
    public $Professionals;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Professionals',
        'app.Cities',
        'app.ProfessionalPhones',
        'app.Ratings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Professionals') ? [] : ['className' => ProfessionalsTable::class];
        $this->Professionals = TableRegistry::getTableLocator()->get('Professionals', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Professionals);

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
