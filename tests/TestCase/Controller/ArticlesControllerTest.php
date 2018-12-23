<?php
namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ArticlesController Test Case
 */
class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
        'app.Articles',
        'app.Tags',
        'app.ArticlesTags'
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

    public function test記事が追加されると記事一覧にリダイレクトする()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
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

    public function testバリデーションエラーだと追加できずエラーメッセージが表示される()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
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

    public function test記事編集ページにアクセスできる()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/articles/edit/CakePHP3-chutoriaru');

        $this->assertResponseContains('記事の編集');
        $this->assertResponseContains('CakePHP3 チュートリアル');
    }

    public function test記事を更新し記事一覧にリダイレクトする()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->session(['Auth.User.id' => 1]);
        $this->post('/articles/edit/CakePHP3-chutoriaru', [
            // タイトルを変更する
            'title' => '1時間で分かるCakePHP3 チュートリアル',
            'body' => 'このチュートリアルは簡単な CMS アプリケーションを作ります。 はじめに CakePHP のインストールを行い、データベースの作成、 そしてアプリケーションを素早く仕上げるための CakePHP が提供するツールを使います。',
            'tag_string' => 'PHP,CakePHP',
        ]);
        $this->assertRedirect('/articles');
        $this->assertSession('Your article has been updated.', 'Flash.flash.0.message');

        $this->get('/articles');
        $this->assertResponseContains('1時間で分かるCakePHP3 チュートリアル');
    }

    public function testバリデーションエラーだと更新できずエラーメッセージが表示される()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->session(['Auth.User.id' => 1]);
        $this->post('/articles/edit/CakePHP3-chutoriaru', [
            // タイトルを変更する
            'title' => '1時間で分かるCakePHP3 チュートリアル',
            'body' => '',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('Unable to update your article.');

        $this->get('/articles');
        $this->assertResponseNotContains('1時間で分かるCakePHP3 チュートリアル');
    }

    public function test記事を削除してその後記事一覧にリダイレクトする()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->session(['Auth.User.id' => 1]);
        $this->post('/articles/delete/CakePHP3-chutoriaru');

        $this->assertRedirect('/articles');

        $this->get('/articles');
        $this->assertResponseNotContains('CakePHP3 チュートリアル');
    }

    public function testGetリクエストの場合削除しない()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/articles/delete/CakePHP3-chutoriaru');

        $this->assertResponseCode(405);
        $this->get('/articles');
        $this->assertResponseContains('CakePHP3 チュートリアル');
    }

    public function test複数タグを指定してアクセス()
    {
        $this->get('/articles/tagged/php/cakephp');

        $this->assertResponseOk();
        $this->assertResponseRegExp('/Articles tagged with\s+php or cakephp/m');
        $this->assertResponseContains('CakePHP3 チュートリアル');
    }

    public function test存在しないタグを指定してアクセス()
    {
        $this->get('/articles/tagged/undefined-tag');

        $this->assertResponseOk();
        $this->assertResponseRegExp('/Articles tagged with\s+undefined-tag/m');
        $this->assertResponseContains('記事が見つかりませんでした。');
    }
}
