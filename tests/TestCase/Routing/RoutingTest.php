<?php
namespace App\Test\TestCase\Routing;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;

class RoutingTest extends TestCase
{
    /**
     * 正引き ('/url' => 配列)
     * @dataProvider dataTestRouting
     * @param string $url
     * @param array $expected
     * @param array $expectedPass
     */
    public function testRoute($url, $expected, $expectedPass=[])
    {
        $expected['pass'] = $expectedPass;
        $actual = Router::parseRequest(new ServerRequest($url));
        $this->assertSame($actual['controller'], $expected['controller']);
        $this->assertSame($actual['action'], $expected['action']);
        $this->assertSame($actual['pass'], $expected['pass']);
    }

    /**
     * 逆引き (配列 => '/url')
     * @dataProvider dataTestRouting
     * @param string $expected
     * @param array $parsedArray
     */
    public function testReverseRoute($expected, $parsedArray)
    {
        $this->assertSame($expected, Router::url($parsedArray));
    }

    public function dataTestRouting()
    {
        return [
            [
                '/articles/tagged',
                ['controller' => 'Articles', 'action' => 'tags'],
            ],
            [
                '/articles/tagged/funny/cat/gifs',
                ['controller' => 'Articles', 'action' => 'tags', 'funny', 'cat', 'gifs'],
                ['funny', 'cat', 'gifs'],
            ],
            [
                '/articles',
                ['controller' => 'Articles', 'action' => 'index'],
            ],
            [
                '/articles/add',
                ['controller' => 'Articles', 'action' => 'add'],
            ],
            [
                '/',
                ['controller' => 'Pages', 'action' => 'display', 'home'],
                ['home'],
            ],
        ];
    }
}
