<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\User;
use Cake\Auth\DefaultPasswordHasher;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\User Test Case
 */
class UserTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\User
     */
    public $User;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->User = new User();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->User);

        parent::tearDown();
    }

    public function testSetPassword()
    {
        $rawPassword = 'secret';
        $this->User->password = $rawPassword;
        $hashedPassword = $this->User->password;

        // ハッシュ化済み
        $this->assertNotSame($rawPassword, $hashedPassword);

        $hasher = new DefaultPasswordHasher();
        $this->assertTrue($hasher->check($rawPassword, $hashedPassword));
    }
}
