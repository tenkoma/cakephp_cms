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
        'app.users',
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

    public function test記事追加ページにアクセスできる()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/articles/add');

        $this->assertResponseOk();
        $this->assertResponseContains('記事の追加');
    }

    public function test記事が追加されると、記事一覧にリダイレクトする()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->post('/articles/add', [
            'title' => 'Nintendo Switch を購入！',
            'body' => 'クリスマスプレゼントとして買った',
            'tag_string' => 'game,2017',
        ]);

        $this->assertSession('Your article has been saved.', 'Flash.flash.0.message');
        $this->assertRedirect('/articles');

        $this->get('/articles');
        $this->assertResponseContains('Nintendo Switch を購入！');
    }

    public function testバリデーションエラーだと追加できず、エラーメッセージが表示される()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->post('/articles/add', [
            'title' => 'Nintendo Switch を購入！',
            'body' => '',
            'tag_string' => '',
        ]);

        $this->assertResponseOk();
        $this->assertResponseContains('Unable to add your article.');

        $this->get('/articles');
        $this->assertResponseNotContains('Nintendo Switch を購入！');
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
