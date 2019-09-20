<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfessionalsSuggestionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProfessionalsSuggestionsTable Test Case
 */
class ProfessionalsSuggestionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProfessionalsSuggestionsTable
     */
    public $ProfessionalsSuggestions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProfessionalsSuggestions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProfessionalsSuggestions') ? [] : ['className' => ProfessionalsSuggestionsTable::class];
        $this->ProfessionalsSuggestions = TableRegistry::getTableLocator()->get('ProfessionalsSuggestions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProfessionalsSuggestions);

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
}
