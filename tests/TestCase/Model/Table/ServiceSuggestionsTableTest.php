<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceSuggestionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceSuggestionsTable Test Case
 */
class ServiceSuggestionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceSuggestionsTable
     */
    public $ServiceSuggestions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ServiceSuggestions',
        'app.Subcategories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ServiceSuggestions') ? [] : ['className' => ServiceSuggestionsTable::class];
        $this->ServiceSuggestions = TableRegistry::getTableLocator()->get('ServiceSuggestions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ServiceSuggestions);

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
