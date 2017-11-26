<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArticlesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArticlesTable Test Case
 */
class ArticlesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ArticlesTable
     */
    public $ArticlesTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.articles',
        'app.tags',
        'app.articles_tags'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Articles') ? [] : ['className' => ArticlesTable::class];
        $this->ArticlesTable = TableRegistry::get('Articles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ArticlesTable);

        parent::tearDown();
    }

    public function testValidationDefault()
    {
        // エラーが無いとき
        $article = $this->ArticlesTable->newEntity([
            'title' => str_repeat('a', 10),
            'body' => str_repeat('b', 256),
        ]);
        $expected = [];
        $this->assertSame($expected, $article->getErrors());

        // 必須項目が空のとき
        $emptyArticle = $this->ArticlesTable->newEntity([
            'title' => '',
            'body' => '',
        ]);
        $expected = [
            'title' => ['_empty' => 'This field cannot be left empty'],
            'body' => ['_empty' => 'This field cannot be left empty'],
        ];
        $this->assertSame($expected, $emptyArticle->getErrors());

        // 文字数が少ないとき
        $lessArticle = $this->ArticlesTable->newEntity([
            'title' => str_repeat('a', 9),
            'body' => str_repeat('b', 9),
        ]);
        $expected = [
            'title' => ['minLength' => 'The provided value is invalid'],
            'body' => ['minLength' => 'The provided value is invalid'],
        ];
        $this->assertSame($expected, $lessArticle->getErrors());

        // 文字数が多いとき
        $moreArticle = $this->ArticlesTable->newEntity([
            'title' => str_repeat('a', 256),
            'body' => str_repeat('b', 256),
        ]);
        $expected = [
            'title' => ['maxLength' => 'The provided value is invalid'],
        ];
        $this->assertSame($expected, $moreArticle->getErrors());
    }
}
