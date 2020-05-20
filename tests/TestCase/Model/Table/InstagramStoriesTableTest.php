<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InstagramStoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InstagramStoriesTable Test Case
 */
class InstagramStoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InstagramStoriesTable
     */
    public $InstagramStories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InstagramStories',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InstagramStories') ? [] : ['className' => InstagramStoriesTable::class];
        $this->InstagramStories = TableRegistry::getTableLocator()->get('InstagramStories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InstagramStories);

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
