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
        'app.Articles',
        'app.Tags',
        'app.ArticlesTags',
        'app.Users',
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

    /**
     * articles 追加
     */
    public function testSaveInsert()
    {
        $newArticle = $this->ArticlesTable->newEntity([
            'user_id' => 1,
            'title' => 'CakePHP テスト',
            'body' => str_repeat('🍺', 10),
            'tag_string' => 'PHP',
        ]);
        $this->ArticlesTable->save($newArticle);

        $article = $this->ArticlesTable->get($newArticle->id, [
            'contain' => ['tags'],
        ]);

        // スラグ
        $this->assertSame('CakePHP-tesuto', $article->slug);

        // タグに変換
        $this->assertSame('PHP', $article->tags[0]->title);
    }

    public function testSaveUpdate()
    {
        $article = $this->ArticlesTable->get(1);
        $this->assertSame('CakePHP3-chutoriaru', $article->slug);
        $article = $this->ArticlesTable->patchEntity($article, [
            'title' => 'CakePHP3 Tutorial',
        ]);
        $this->ArticlesTable->save($article);

        $newArticle = $this->ArticlesTable->get(1);

        // title が変わってもスラグは変化しない
        $this->assertSame('CakePHP3 Tutorial', $newArticle->title);
        $this->assertSame('CakePHP3-chutoriaru', $newArticle->slug);
    }

    public function testFindTagged()
    {
        // タグなし
        $notTaggedArticle = $this->ArticlesTable
            ->find('tagged', ['tags' => []])
            ->contain(['Tags'])
            ->first();
        $this->assertEmpty($notTaggedArticle->tags);

        // タグあり
        $taggedArticle = $this->ArticlesTable
            ->find('tagged', ['tags' => ['PHP']])
            ->contain(['Tags'])
            ->first();
        $tags = new \Cake\Collection\Collection($taggedArticle->tags);
        $this->assertNotEmpty($tags->filter(function($tag) {
            return $tag->title === 'PHP';
        }));
    }
}
