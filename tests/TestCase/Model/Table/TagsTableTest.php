<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TagsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TagsTable Test Case
 */
class TagsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TagsTable
     */
    public $TagsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Tags',
        'app.Articles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $tableLocator = TableRegistry::getTableLocator();
        $config = $tableLocator->exists('Tags') ? [] : ['className' => TagsTable::class];
        $this->TagsTable = $tableLocator->get('Tags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TagsTable);

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
