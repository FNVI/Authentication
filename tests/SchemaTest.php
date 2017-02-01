<?php
use PHPUnit\Framework\TestCase;
use FNVi\Authentication\Schemas\Session;
use FNVi\Authentication\Schemas\User;
/**
 * Description of SchemaTest
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class SchemaTest extends TestCase{
    
    public function testCollection(){
        
        User::setCollection("usertest");
        Session::setCollection("sessiontest");
        
        
        
    }
    
}
