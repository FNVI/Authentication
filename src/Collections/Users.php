<?php
namespace FNVi\Authentication\Collections;

use FNVi\Mongo\Collection;
/**
 * Description of Users
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Users extends Collection{
    
    public function __construct() {
        parent::__construct("users");
    }
    
}
