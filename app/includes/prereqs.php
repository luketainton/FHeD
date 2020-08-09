<?php

// Composer
require_once __DIR__ . "/../vendor/autoload.php";

// PHPDotEnv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

// Database auto-generation
if (file_exists("/../includes/install.php")) {
  return;
  add_action('run_db_populate');
}

function run_db_populate() {
    // all my glorious one-time-magic.
    include( "/../includes/install.php" );
   // after all execution rename your file;
   rename( "/../includes/install.php", "/../includes/install-backup.php");
}

// Session
session_start();

// Database
$db = new PDO("mysql:host=".$_ENV['MYSQL_HOST'].";dbname=".$_ENV['MYSQL_DB'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// OpenID Connect
use Jumbojett\OpenIDConnectClient;
$oidc = new OpenIDConnectClient($_ENV['OIDC_HOST'], $_ENV['OIDC_CLIENT_ID'], $_ENV['OIDC_CLIENT_SECRET']);
if ($_ENV['OIDC_DISABLE_SSL'] == "true") {
  $oidc->setVerifyHost(false);
  $oidc->setVerifyPeer(false);
}


// Custom functions

require_once __DIR__ . "/app_functions.php";

function oidc_set_vars($sub, $uid, $fname, $lname, $email) {
  $_SESSION['uuid'] = $sub;
  $_SESSION['username'] = $uid;
  $_SESSION['given_name'] = $fname;
  $_SESSION['family_name'] = $lname;
  $_SESSION['full_name'] = $fname . " " . $lname;
  $_SESSION['email'] = $email;
}

function is_signed_in() {
  if (isset($_SESSION['is_signed_in'])) {
    return true;
  } else {
    return false;
  }
}
