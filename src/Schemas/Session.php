<?php

namespace FNVi\Authentication\Schemas;

use FNVi\Mongo\Schema;
use MongoDB\BSON\ObjectID;


/**
 * Description of Session
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Session extends Schema{
    
    protected static $strict = true;

    /**
     *
     * @var ObjectID
     */
    protected $user_id;
    protected $timestamp;
    
    public function __construct() {
        parent::__construct();
        $this->timestamp = $this->timestamp();
        $_SESSION["key"] = $this->_id."";
        $this->save();
    }
    
    /**
     * Stores the user against the session
     * @param ObjectID $user
     */
    public function setUser(ObjectID $user){
        $this->user_id = $user;
        $this->save();
    }
    /**
     * Gets the user from the session
     * @return ObjectID
     */
    public function getUser(){
        return $this->user_id;
    }

    public function endSession(){
        unset($_SESSION["key"]);
    }
    
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
