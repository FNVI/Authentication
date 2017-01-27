<?php

namespace FNVi\Authentication\Collections;

use FNVi\Mongo\Collection;

/**
 * Description of Sessions
 *
 * @author Joe Wheatley <joew@fnvi.co.uk>
 */
class Sessions extends Collection{
    
    public function __construct() {
        parent::__construct("sessions");
    }
}
