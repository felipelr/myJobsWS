<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfessionalCommentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProfessionalCommentsTable Test Case
 */
class ProfessionalCommentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProfessionalCommentsTable
     */
    public $ProfessionalComments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProfessionalComments',
        'app.Professionals',
        'app.Clients'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProfessionalComments') ? [] : ['className' => ProfessionalCommentsTable::class];
        $this->ProfessionalComments = TableRegistry::getTableLocator()->get('ProfessionalComments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProfessionalComments);

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
