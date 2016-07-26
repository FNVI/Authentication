<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FNVi\Authentication\Schemas;

use FNVi\Mongo\Schema;
use FNVi\Authentication\Collections\Sessions;
use FNVi\Authentication\Collections\Users;


/**
 * Description of Session
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Session extends Schema{
    
    protected $user_id;
    protected $timestamp;
    
    public function __construct() {
        parent::__construct();
        $this->timestamp = $this->timestamp();
        $_SESSION["key"] = $this->_id."";
        $this->store();
    }
    
    public function setUser(User $user){
        $this->user_id = $user->_id;
        $this->store();
    }
    
    public function getUser(){
        if($this->user_id)
        {
            return (new Users())->findOne(["_id"=>$this->user_id]);
        }
        return null;
    }


    public function endSession(){
        $this->delete();
    }
    
    public static function keyExists(){
        return key_exists("key", $_SESSION);
    }
    
    private static function getKey(){
        return $_SESSION["key"];
    }
  
    public static function start(){
        if(session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
    }
    
    /**
     * 
     * @return FNVi\Authentication\Schemas\Session
     */
    public static function getSession(){
        $session = null;
        if(self::keyExists()){
            $session = self::loadSession();
        }
        return $session ? $session : new Session();
    }

    private static function loadSession(){
        return (new Sessions())->loadSession(self::getKey());
    }
    
    public static function stop() {
        if (session_destroy()) {
            self::getSession()->delete();
            return true;
        }
        return false;
    }

}
Session::start();
