<?php

include 'settings.php';
include '../../vendor/autoload.php';

use FNVi\Authentication\Auth;

$auth = new Auth();
$auth->logout();