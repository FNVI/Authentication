<?php
use FNVi\Mongo\Database;
use FNVi\Authentication\Auth;

include 'vendor/autoload.php';

// ob_start required to allow session headers to be set!
ob_start();

Database::connect("mongodb://localhost/testdb");
Auth::setMaxLoginAttempts(3);