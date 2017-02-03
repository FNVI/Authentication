<?php
use PHPUnit\Framework\TestCase;
use FNVi\Authentication\Auth;
use FNVi\Authentication\Schemas\Session;
use FNVi\Authentication\Schemas\User;
/**
 * Description of AuthTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class AuthTest extends TestCase{
    
    protected $auth;
    protected $username = "testUser";
    protected $password = "password";
    
    protected function setUp() {
        $this->auth = new Auth();
        $this->user = new User("testuser","password");
    }

    public function testSignUp(){
        $result = $this->auth->registerUser(new User($this->username,$this->password));
    }
    
    public function testLogin(){
        $this->assertFalse($this->auth->isLoggedIn());
        $this->auth->login($this->username, $this->password);
        $this->assertTrue($this->auth->isLoggedIn());
    }
       
    public static function tearDownAfterClass() {
        Session::stop();
    }
}
