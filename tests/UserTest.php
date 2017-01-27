<?php
use PHPUnit\Framework\TestCase;
use FNVi\Authentication\Schemas\User;
/**
 * Description of UserTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class UserTest extends TestCase{
    
    /**
     *
     * @var User
     */
    protected $user;

    protected $username = "user";
    protected $password = "password";

    protected function setUp() {
        $this->user = new User($this->username, $this->password);
    }
    
    public function testHashes(){
        $password = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $hash = User::generateHash($password);
        $this->assertFalse($hash === User::generateHash($password));
        $this->assertTrue(User::checkHash($password, $hash));
    }
    
    public function testConstructor(){
        $this->assertTrue($this->user->checkPassword($this->password));
        $this->assertFalse($this->user->checkPassword("incorrect"));
    }
    
    public function testPermissions(){
        $permissions = ["canEditSomething","canViewSomething"];
        
        foreach($permissions as $permission){
            $this->assertFalse($this->user->hasPermission($permission));
        }
        
        $this->user->grantPermissions($permissions);
        
        foreach($permissions as $permission){
            $this->assertTrue($this->user->hasPermission($permission));
        }
        
        $this->user->revokePermissions([$permissions[1]]);
        
        $this->assertFalse($this->user->hasPermission($permissions[1]));
        $this->assertTrue($this->user->hasPermission($permissions[0]));
    }
    
}
