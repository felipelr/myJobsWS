<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HighlightsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HighlightsTable Test Case
 */
class HighlightsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HighlightsTable
     */
    public $Highlights;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Highlights',
        'app.Professionals',
        'app.Subcategories',
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
        $config = TableRegistry::getTableLocator()->exists('Highlights') ? [] : ['className' => HighlightsTable::class];
        $this->Highlights = TableRegistry::getTableLocator()->get('Highlights', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Highlights);

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
