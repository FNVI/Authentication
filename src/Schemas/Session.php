<?php

namespace FNVi\Authentication\Schemas;

use FNVi\Mongo\Schema;
use FNVi\Mongo\Stamp;
use FNVi\Authentication\Schemas\User;


/**
 * Description of Session
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Session extends Schema{
    
    protected static $strict = true;

    /**
     *
     * @var Stamp
     */
    public $user;
    protected $timestamp;
    
    public function __construct() {
        parent::__construct();
        $this->timestamp = $this->timestamp();
        $_SESSION["key"] = $this->_id."";
        $this->save();
    }
    
    /**
     * Stores the user against the session
     * 
     * This should work with any user object, even those that extends the User class
     * @param User $user
     */
    public function setUser(User $user){
        $this->user = $user->stamp();
        $this->save();
    }
    
    /**
     * Gets the user from the session
     * 
     * This should work with any user object, even those that extend the User class
     * @return Stamp
     */
    public function getUser(){
        return $this->user;
    }
    /**
     * Ends the current session
     * 
     * This unsets the session key in the browser to achieve ending the session
     */
    public function endSession(){
        unset($_SESSION["key"]);
    }
    
    /**
     * Checks a session key exists for the user
     * @return string
     */
    public static function keyExists(){
        return key_exists("key", $_SESSION);
    }
    
    /**
     * Gets the session key
     * @return string
     */
    private static function getKey(){
        return $_SESSION["key"];
    }
    
    /**
     * Starts or resumes a current session
     */
    public static function start(){
        if(session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
    }
    
    /**
     * Gets the current session
     * @return Session
     */
    public static function getSession(){
        $session = null;
        if(self::keyExists()){
            $session = self::loadFromID(self::getKey());
        }
        return $session ? $session : new Session();
    }
    
    public static function stop() {
        if(self::getSession()->endSession()) {
            session_destroy();
            return true;
        }
        return false;
    }

}
Session::start();
