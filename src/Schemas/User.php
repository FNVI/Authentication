<?php

namespace FNVi\Authentication\Schemas;

use FNVi\Mongo\Schema;
use FNVi\Authentication\Collections\Users;

/**
 * Description of User
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class User extends Schema{
    
    /**
     * Sets the Schema to be strict
     * @var boolean
     */
    protected static $strict = true;

    
    public $username;
    protected $password;
    public $email;
    protected $emailConfirmed = false;
    protected $failedLogins = 0;
    protected $permissions = [];

    public function __construct($username, $password = null) {
        parent::__construct(new Users());
        $this->username = $username;
        if($password){
            $this->setPassword($password);
        }
    }
    
    /**
     * Grants permissions to a user
     * @param array $permissions
     */
    public function grantPermissions(array $permissions){
        $this->permissions = array_merge($this->permissions, $permissions);
    }
    
    /**
     * Revokes permissions from a user
     * @param array $permissions
     */
    public function revokePermissions(array $permissions){
        $this->permissions = array_diff($this->permissions, $permissions);
    }
    
    /**
     * Checks if a user has permission
     * @param mixed $permission
     * @return boolean
     */
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
    
    /**
     * Sets the users password
     * @param string $password
     */
    public function setPassword($password){
        $this->password = self::generateHash($password);
    }

    /**
     * Hashes a string for password storage
     * @param string $password
     * @return string
     */
    public static function generateHash($password){
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            return crypt($password, $salt);
        }
        return NULL;
    }
    
    /**
     * Checks a string against a hashed value
     * @param string $str
     * @param string $salt
     * @return boolean
     */
    public static function checkHash($str, $salt){
        return crypt($str, $salt) == $salt;
    }
    
    /**
     * Checks the users password is correct
     * @param string $password
     * @return boolean
     */
    public function checkPassword($password){
        return self::checkHash($password, $this->password);
    }
    
    /**
     * Gets a stamp of the user
     * @param array $fields
     * @return array
     */
    public function stamp(array $fields = []){
        return parent::stamp(array_merge(["username"],$fields));
    }
}
