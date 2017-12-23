<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ArticlesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ArticlesController Test Case
 */
class ArticlesControllerTest extends IntegrationTestCase
{

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

    public function test記事一覧を表示()
    {
        $this->get('/articles');

        $this->assertResponseOk();
        $this->assertResponseContains('CakePHP3 チュートリアル');
        $this->assertResponseContains('Happy new year');
    }

    public function test記事詳細ページを表示()
    {
        $this->get('/articles/view/CakePHP3-chutoriaru');

        $this->assertResponseOk();
        $this->assertResponseContains('CakePHP3 チュートリアル'); // title
        $this->assertResponseContains('このチュートリアルは簡単な ' .
            'CMS アプリケーションを作ります。'); // body
    }

    public function test記事詳細ページが存在しない()
    {
        $this->get('/articles/view/not-found-articles');

        $this->assertResponseCode(404);
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test tags method
     *
     * @return void
     */
    public function testTags()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
