<?php

namespace FNVi\Authentication\Collections;

use FNVi\Mongo\Collection;
use MongoDB\BSON\ObjectID;
/**
 * Description of Sessions
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Sessions extends Collection{
    
    public function __construct() {
        parent::__construct();
    }

    public function loadSession($id){
        return $this->findOne(["_id"=>new ObjectID($id."")]);
    }
}
