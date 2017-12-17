<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\UsersController Test Case
 */
class UsersControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.articles'
    ];

    /**
     * ログインページが表示される
     */
    public function testLoginShow()
    {
        $this->get('/users/login');
        $this->assertResponseOk();
        $this->assertResponseContains('ログイン');
    }

    /**
     * ログイン失敗
     */
    public function testLoginFailed()
    {
        $this->post('/users/login', [
            'email' => 'myname@example.com',
            'password' => 'wrongpassword',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('ユーザー名またはパスワードが不正です。');
    }

    /**
     * ログイン成功
     */
    public function testLoginSucceed()
    {
        $this->post('/users/login?redirect=%2Farticles%2Fadd', [
            'email' => 'myname@example.com',
            'password' => 'password',
        ]);
        $this->assertRedirect('/articles/add');
        $this->assertSession(1, 'Auth.User.id');
    }
}
