<?php
use PHPUnit\Framework\TestCase;
use FNVi\Authentication\Schemas\Session;

/**
 * Description of SessionTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class SessionTest extends TestCase{
    
    protected $session;
    
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
    
    public function testEndSession(){
        $this->session->endSession();
        $result = Session::getSession();
        $this->assertNotEquals($this->session, $result);
    }
    
    public static function tearDownAfterClass() {
        Session::stop();
        parent::tearDownAfterClass();
    }
    
}
