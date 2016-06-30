<?php

namespace FNVi\Authentication\Schemas;

use FNVi\Mongo\Schema;

/**
 * Description of User
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class User extends Schema{
    
    protected $username;
    protected $password;
    public $email;
    protected $tokens;
    protected $emailConfirmed = false;
    protected $failedLogins = 0;
    protected $permissions = [];

    public function __construct($username, $password = null) {
        parent::__construct();
        $this->username = $username;
        if($password){
            $this->setPassword($password);
        }
    }
    
    public function grantPermissions(array $permissions){
        $this->permissions = array_merge($this->permissions, $permissions);
    }
    
    public function revokePermissions(array $permissions){
        $this->permissions = array_diff($this->permissions, $permissions);
    }
    
        
    public function hasPermission($permission){
        if($permission === "login"){
            return true;
        }
        if(!$this->permissions)
        {
            return false;
        }
        return in_array($permission, $this->permissions);
    }
    
    public function setPassword($password){
        $this->password = self::generateHash($password);
    }


    public static function generateHash($password){
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            return crypt($password, $salt);
        }
        return NULL;
    }
    
    public static function checkHash($str, $salt){
        return crypt($str, $salt) == $salt;
    }
    
    public function checkPassword($password){
        return self::checkHash($password, $this->password);
    }
    
    public function stamp(){
        return parent::stamp(["username"]);
    }
        
    public function getUsername(){
        return $this->username;
    }
        
    public function markInactive(){
        $this->active = false;
    }
}
