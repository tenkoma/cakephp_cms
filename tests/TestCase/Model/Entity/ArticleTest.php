<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Article;
use App\Model\Entity\Tag;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Article Test Case
 */
class ArticleTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Article
     */
    public $Article;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Article = new Article();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Article);

        parent::tearDown();
    }

    /**
     * @dataProvider dataTestTagString
     */
    public function testTagString($tags, $expected)
    {
        $tagEntities = [];
        foreach ($tags as $tagTitle) {
            $tagEntities[] = new Tag(['title' => $tagTitle]);
        }
        $article = new Article(['tags' => $tagEntities]);
        $this->assertSame($expected, $article->tag_string);
    }

    public function dataTestTagString()
    {
        return [
            [[''], ''],
            [['Torte'], 'Torte'],
            [['Torte', 'Financier', 'Macaron'], 'Torte, Financier, Macaron'],
        ];
    }
}
