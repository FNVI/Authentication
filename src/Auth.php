<?php

namespace FNVi\Authentication;

use FNVi\Authentication\Schemas\Session;
use FNVi\Authentication\Collections\Users;
use FNVi\Authentication\Schemas\User;
use FNVi\Email;

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
    
    public function login($username, $password){
        
        $user = $this->users->findOne(["username"=>$username]);
        if($user){
            if($user->checkPassword($password))
            {
                $this->user = $user;
                $this->session->setUser($user);
                if($_SESSION["url"]){
                    $this->redirect($_SESSION["url"], "successfully logged in");
                    unset($_SESSION["url"]);
                }
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
    
    public function confirmEmail($email,$token){
//        return $this->users->onlyDeleted()->update(["tokens.confirm email"=>$token])->clear(["tokens.confirm email"])->recover()->updateOne();
        $user = $this->users->onlyDeleted()->findOne(["email"=>$email]);
        if($user){
            if(User::checkHash($user->getId().floor(strtotime("now") / 3600),$token)){
                $this->users->onlyDeleted()->update(["email"=>$email])->recover()->updateOne();
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
    
    public function resetPassword($email, $token, $password = ""){
        $user = $this->users->findOne(["email"=>$email]);
        if($user){
            if(User::checkHash($user->getId().floor(strtotime("now") / 3600),$token)){
                $user->setPassword($password);
                $user->store();
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
    }
    
    /**
     * 
     * @param \FNVi\Authentication\FNVi\Authentication\Schemas\User $user
     */
    public function registerUser(User $user, $emailMessage = "", $confirmAddress = false){
        $subject = "Welcome!";
        $token = "";
        if($confirmAddress){
            $user->markInactive();
            $token = User::generateHash($user->getId().floor(strtotime("now") / 3600));
            $url = "http://".$_SERVER['HTTP_HOST'].'/'.constant("URL_CONFIRM_EMAIL").'?'.http_build_query(["email"=>$user->email,"token"=>$token]);
            $emailMessage .= "<br>Click <a href='$url'>here</a> to confirm your email address";
            $subject .= " Please confirm email address";
        }
        $ouptut = $this->users->insertOne($user);
        if($ouptut->getInsertedCount() && $emailMessage != ""){
            $email = new Email($user->email, constant("EMAIL_ADDRESS"), $subject);
            if($email->message($emailMessage)->send()){
                $this->message = "Please confirm your email address!";
                return true;
            }
        }
        return $token;
    }
    
    public function forgottenPassword($email, $template){
        
        $user = $this->users->findOne(["email"=>$email]);
        if($user){
            $token = User::generateHash($user->getId().floor(strtotime("now") / 3600));
            $vars = [
                "email"=>$email,
                "token"=>$token
            ];
            $url = "http://".$_SERVER['HTTP_HOST'].'/'.constant("URL_FORGOTTEN_PASSWORD").'?'.http_build_query($vars);
            $emailObject = new Email();
            $emailObject  ->to($email)
                    ->from(EMAIL_ADDRESS)
                    ->subject("Forgotten password")
                    ->message("Click <a href='$url'>here</a> to reset password! ");
            if($emailObject->send()){
                $this->message = "Email sent to $email";
                return $token;
            }
        }
        else
        {
            $this->message = "User not found";
        }
        return $token;
    }
}
