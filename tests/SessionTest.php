<?php
use PHPUnit\Framework\TestCase;
use FNVi\Authentication\Schemas\User;
use FNVi\Authentication\Schemas\Session;

/**
 * Description of SessionTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class SessionTest extends TestCase{
    
    /**
     * 
     * @return Session
     */
    public function testConstructor(){
        
        $this->assertFalse(Session::keyExists());
        $session = new Session();
        $this->assertTrue(Session::keyExists());
        return $session;
    }
    
    /**
     * @depends testConstructor
     * @param Session $session
     * @return Session
     */
    public function testSetUser(Session $session){
        $user = new User("user","password");
        $user->store();
        $session->setUser($user);
        $session->store();
        return $session;
    }
    
    /**
     * @depends testSetUser
     * @param Session $session
     * @return Session
     */
    public function testGetUser(Session $session){
        
        $user = $session->getUser();
        
        $this->assertNotNull($user);
        $this->assertEquals("user", $user->username);
        return $session;
    }
    
    /**
     * @depends testGetUser
     * @param Session $session
     * @return Session
     */
    public function testGetSession(Session $session){
        $result = Session::getSession();
        $this->assertEquals($session, $result);
        return $session;
    }
    
    public static function tearDownAfterClass() {
        Session::stop();
        parent::tearDownAfterClass();
    }
    
}
