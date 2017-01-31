<?php

namespace FNVi\Authentication;

use FNVi\Authentication\Schemas\Session;
use FNVi\Authentication\Collections\Users;
use FNVi\Authentication\Schemas\User;

/**
 * Description of Auth
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Auth {
    
    protected static $loginAttemptsAllowed = 99;


    /**
     * The current user
     * @var User
     */
    private $user = null;
    /**
     *
     * @var Users 
     */
    private $users;
    /**
     *
     * @var Session 
     */
    private $session;
    
    private $authorised = true;
    
    public function __construct() {
        $this->session = Session::getSession();
        $this->user = $this->session->getUser();
        $this->users = new Users();
    }
    
    /**
     * Sets the permissions required to view the page
     * 
     * Internally this checks the current users permissions
     * @param string $permission
     * @return Auth
     */
    public function requires($permission){
        if(!$this->user || !$this->user->hasPermission($permission)){
            $this->authorised = false;
        }
        return $this;
    }
    
    /**
     * Checks if the current user is authorised
     * @return boolean
     */
    public function isAuthorised(){
        return $this->authorised;
    }
    
    /**
     * Sets if the page requires logging in
     * @return boolean
     */
    public function requiresLogin(){
        return $this->requires("login");
    }
        
    /**
     * 
     * @return User
     */
    public function getUser(){
        return $this->user;
    }
    
    /**
     * Logs in and sets the current user for the session
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public function login($username, $password){
        /* @var $user User */
        $user = $this->users->findOne(["username"=>$username]);
        if($user){
            if($user->checkPassword($password) && $user->failedLoginAttempts < self::$loginAttemptsAllowed )
            {
                $user->failedLoginAttempts = 0;
                $user->save();
                $this->user = $user;
                $this->session->setUser($user);
                return true;
            } else {
                $user->failedLoginAttempts++;
                $user->save();
                return false;
            }
        }
        return false;
    }
        
    /**
     * Used to logout the curent user
     */
    public function logout(){
        if(Session::stop()){
            $this->authorised = false;
        }
    }
    
    /**
     * 
     * @param User $user
     */
    public function registerUser(User $user){
       $result = $this->users->insertOne($user);
       return $result->getInsertedCount() ? true : false;
    }
    
    public static function setMaxLoginAttempts($number){
        self::$loginAttemptsAllowed = $number;
    }
}
