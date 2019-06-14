<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfessionalPhonesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProfessionalPhonesTable Test Case
 */
class ProfessionalPhonesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProfessionalPhonesTable
     */
    public $ProfessionalPhones;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProfessionalPhones',
        'app.Professionals'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProfessionalPhones') ? [] : ['className' => ProfessionalPhonesTable::class];
        $this->ProfessionalPhones = TableRegistry::getTableLocator()->get('ProfessionalPhones', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProfessionalPhones);

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
