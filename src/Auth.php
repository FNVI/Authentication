<?php

namespace FNVi\Authentication;

use FNVi\Authentication\Schemas\Session;
use FNVi\Authentication\Collections\Users;
use FNVi\Authentication\Schemas\User;
use MongoDB\BSON\ObjectID;

/**
 * Description of Auth
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Auth {
    
    private $user = null;
    /**
     *
     * @var FNVi\Authentication\Collections\Users 
     */
    private $users;
    /**
     *
     * @var type 
     */
    private $session;
    private $AJAX = false;
    private $message = "";
    
    public function __construct() {
        $this->session = Session::getSession();
        $this->user = $this->session->getUser();
        $this->users = new Users();
    }
    
    public function requires($permission){
        if(!$this->user){
            $this->sendUnauthorised();
        }elseif(!$this->user->hasPermission($permission)){
            $this->sendForbidden();
        }
        return $this;
    }
    
    public function requiresLogin(){
        return $this->requires("login");
    }
    
    public function AJAX(){
        $this->AJAX = true;
        return $this;
    }
    
    /**
     * 
     * @return FNVi\Authentication\Schemas\User
     */
    public function getUser(){
        return $this->user;
    }
    
    public function login(User $user, $password){
        $user = $this->users->findOne(["_id"=>$user->getId()]);
        if($user){
            if($user->checkPassword($password))
            {
                $this->user = $user;
                $this->session->setUser($user);
//                if($_SESSION["url"]){
//                    $this->redirect($_SESSION["url"], "successfully logged in");
//                    unset($_SESSION["url"]);
//                }
                return true;
            }
        }
        $this->message = "Cannot login, check details";
        return false;
    }
    
    public function getMessage(){
        return $this->message;
    }
    
    public function logout(){
        if(Session::stop()){
            $this->sendUnauthorised();
        }
    }
    
    private function sendUnauthorised(){
        $_SESSION["url"] = $_SERVER["SCRIPT_NAME"];
        $this->sendError(401, "You need to be authorised to perform this request, please login" , constant("REDIRECT_UNAUTHORIZED"));
    }
    
    private function sendForbidden(){
        $this->sendError(403, "You do not have authorisation to perform this request", constant("REDIRECT_FORBIDDEN"));
    }
    
    private function sendError($code,$message,$redirectURL = null){
        http_response_code($code);
        if(!$this->AJAX && $redirectURL){
           $this->redirect($redirectURL,$message); 
        }
        else {
            echo json_encode($message);
            die();
        }
    }
    
    private function redirect($url,$message = ""){
        $url = $this->redirectUrl($url);
        if(DEVELOPMENT)
        {
            echo json_encode($message);
            echo "<BR><BR>redirect to <a href=\"$url\">$url</a><BR><BR>Session = ".  json_encode($_SESSION);
            die();
        }
        else if(!$this->AJAX)
        {
            header("location: $url");
        }
    }
    
    private function redirectUrl($url){
        if(substr($url, 0, 1) === '/')
        {
            return "http://".$_SERVER['HTTP_HOST'] . $url;
        }
        return $url;
    }
    
    public function confirmEmail(User $user, $token){
        if($user){
            if(Auth::checkToken($user->getId(),$token)){
                $user->recover();
                $this->message = "Email confirmed";
                return true;
            }
            else
            {
                $this->message = "Invalid token";
            }
        }
        else
        {
            $this->message = "Invalid email address";
        }
        return false;
    }
    
    public function resetPassword(User $user, $token, $password){
        if($user){
            if(Auth::checkToken($user->getId(),$token)){
                $user->setPassword($password);
                $user->store();
                return true;
            }
            else
            {
                $this->message = "Invalid token";
            }
        }
        else
        {
            $this->message = "Invalid email address";
        }
        return false;
    }
    
    /**
     * 
     * @param User $user
     */
    public function registerUser(User $user, $confirmAddress){
        $token = true;
        if($confirmAddress){
            $user->markInactive();
            $token = Auth::issueToken($user->getId());
        }
        $ouptut = $this->users->insertOne($user);
        if($ouptut->getInsertedCount()){
            $this->message = "Please confirm your email address!";
            return $token;
        }
        return false;
    }
    
    /**
     * 
     * @param User $user
     * @return string
     */
    public function forgottenPassword(User $user){
        return Auth::issueToken($user->getId());
    }
    
    public static function issueToken(ObjectID $id){
        return User::generateHash($id.self::time());
    }
    
    public static function checkToken(ObjectID $id, $token){
        return User::checkHash($id.self::time(),$token);
    }
    
    private static function time(){
        return floor(strtotime("now") / 3600);
    }
}
