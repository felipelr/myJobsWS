<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FavoriteProfessionalsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FavoriteProfessionalsTable Test Case
 */
class FavoriteProfessionalsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FavoriteProfessionalsTable
     */
    public $FavoriteProfessionals;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FavoriteProfessionals',
        'app.Professionals',
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
        $config = TableRegistry::getTableLocator()->exists('FavoriteProfessionals') ? [] : ['className' => FavoriteProfessionalsTable::class];
        $this->FavoriteProfessionals = TableRegistry::getTableLocator()->get('FavoriteProfessionals', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FavoriteProfessionals);

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
