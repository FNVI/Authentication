<?php
use PHPUnit\Framework\TestCase;
use FNVi\Authentication\Auth;
use FNVi\Authentication\Schemas\Session;
use FNVi\Authentication\Schemas\User;
use FNVi\Mongo\Database;
use MongoDB\BSON\ObjectID;
/**
 * Description of AuthTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class AuthTest extends TestCase{
    
    protected $user;
    
    protected function setUp() {
        $this->user = new User("user","password");
        $this->user->email = "joew@fnvi.co.uk";
        parent::setUp();
    }

    public function testConstructor(){
        $auth = new Auth();
        return $auth;
    }
    
    /**
     * @depends testConstructor
     * @param Auth $auth
     * @return Auth
     */
    public function testSignup(Auth $auth){
        $token = $auth->registerUser($this->user,true);
        $this->assertNotFalse($token);
        $result = $auth->confirmEmail($this->user, $token);
        $this->assertTrue($result, $auth->getMessage());
        return $auth;
    }
    
    public function testTokens(){
        $id = new ObjectID();
        $token = Auth::issueToken($id);
        $actual = Auth::checkToken($id, $token);
        $this->assertTrue($actual);
    }
        
    public static function tearDownAfterClass() {
        Session::stop();
        parent::tearDownAfterClass();
    }
}
