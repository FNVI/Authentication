<?php
use PHPUnit\Framework\TestCase;
use FNVi\Authentication\Schemas\User;
/**
 * Description of UserTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class UserTest extends TestCase{
    
    
    public function testHashes(){
        $password = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $hash = User::generateHash($password);
        $this->assertFalse($hash === User::generateHash($password));
        $this->assertTrue(User::checkHash($password, $hash));
    }
    
    /**
     * 
     * @return User
     */
    public function testConstructor(){
        $user = new User("user","password");
        
        $this->assertTrue($user->checkPassword("password"));
        
        return $user;
    }
    
    /**
     * @depends testConstructor
     * @param User $user A User object
     */
    public function testPermissions(User $user){
        $permissions = ["canEditSomething","canViewSomething"];
        
        foreach($permissions as $permission){
            $this->assertFalse($user->hasPermission($permission));
        }
        
        $user->grantPermissions($permissions);
        
        foreach($permissions as $permission){
            $this->assertTrue($user->hasPermission($permission));
        }
        
        $user->revokePermissions($permissions);
        
        foreach($permissions as $permission){
            $this->assertFalse($user->hasPermission($permission));
        }
        
    }
    
}
