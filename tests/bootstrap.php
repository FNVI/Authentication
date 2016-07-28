<?php

define("DATABASE", "testdb");
define("MONGOURI","mongodb://localhost");

define("REDIRECT_FORBIDDEN", "forbidden.html");
define("REDIRECT_UNAUTHORIZED", "unauthorised.html");

define("EMAIL_ADDRESS","info@fnvi.co.uk");

define("URL_CONFIRM_EMAIL", "joe/authentication/examples/regular/confirmemail.php?");
define("URL_FORGOTTEN_PASSWORD","joe/authentication/examples/regular/resetpassword.php?");

include 'vendor/autoload.php';

// ob_start required to allow session headers to be set!
ob_start();
