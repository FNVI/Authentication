<?php
use PHPUnit\Framework\TestCase;
use FNVi\Mongo\Database;
use FNVi\Authentication\Schemas\Session;
use FNVi\Authentication\Schemas\User;
use FNVi\Authentication\Collections\Users;

/**
 * Description of SessionTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class SessionTest extends TestCase{
    
    protected $session;
    public static $user;
    
    public static function setUpBeforeClass() {
        self::$user = new User("testUser");
        self::$user->save();
    }
    
    protected function setUp() {
        $this->session = new Session();
    }
    
    public function testKeyExists(){
        $this->assertTrue(Session::keyExists());
    }
    
    public function testGetSession(){
        $result = Session::getSession();
        $this->assertEquals($this->session, $result);
    }
    
    public function testUser(){
        $users = new Users();
        $this->session->setUser(self::$user);
        $test = $this->session->getUser();
        $this->assertEquals(self::$user, $test, get_class($this->session->user->_id));
    }
    
    public function testEndSession(){
        $this->session->endSession();
        $result = Session::getSession();
        $this->assertNotEquals($this->session, $result);
    }
    
    public static function tearDownAfterClass() {
        Session::stop();
        Database::dropDatabase();
    }
    
}
