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

function create_alert($type, $msg) {
  $thisAlert = array($type, $msg);
  array_push($_SESSION['alerts'], $thisAlert);
}

function get_user_name($db, $user_uuid) {
  try {
    $stmt = "SELECT given_name, family_name FROM users WHERE uuid=:uuid";
    $sql = $db->prepare($stmt);
    $sql->bindParam(':uuid', $user_uuid);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $result = $sql->fetchAll();
    $usr = $result[0]['given_name'] . " " . $result[0]['family_name'];
  } catch (PDOException $e) {
    echo("Error: " . $e->getMessage());
  }
  return $usr;
}

function get_my_requests($db) {
  $ticket_stmt = "SELECT * FROM tickets WHERE created_by=:uuid";
  $ticket_sql = $db->prepare($ticket_stmt);
  $ticket_sql->bindParam(':uuid', $_SESSION['uuid']);
  $ticket_sql->execute();
  $ticket_sql->setFetchMode(PDO::FETCH_ASSOC);
  $ticket_result = $ticket_sql->fetchAll();
  return $ticket_result;
}

function get_subscribed_requests($db) {
    $requests = array();
    $sub_tickets_stmt = "SELECT ticket_uuid FROM ticket_subscribers WHERE user_uuid=:uuid";
    $sub_tickets_sql = $db->prepare($sub_tickets_stmt);
    $sub_tickets_sql->bindParam(':uuid', $_SESSION['uuid']);
    $sub_tickets_sql->execute();
    $sub_tickets_sql->setFetchMode(PDO::FETCH_ASSOC);
    $sub_tickets_result = $sub_tickets_sql->fetchAll();
    foreach ($sub_tickets_result as $tkt) {
      $stmt = "SELECT * FROM tickets WHERE uuid=:uuid";
      $sql = $db->prepare($stmt);
      $sql->bindParam(':uuid', $tkt['ticket_uuid']);
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      $result = $sql->fetchAll();
      array_push($requests, $result[0]);
    }
    return $requests;
}

function get_request($db, $uuid) {
  $ticket_stmt = "SELECT * FROM tickets WHERE uuid=:uuid";
  $ticket_sql = $db->prepare($ticket_stmt);
  $ticket_sql->bindParam(':uuid', $uuid);
  $ticket_sql->execute();
  $ticket_sql->setFetchMode(PDO::FETCH_ASSOC);
  $ticket_result = $ticket_sql->fetchAll();
  $request = $ticket_result[0];
  return $request;
}


function get_updates($db, $request) {
  $updates_stmt = "SELECT * FROM ticket_updates WHERE ticket=:uuid";
  $updates_sql = $db->prepare($updates_stmt);
  $updates_sql->bindParam(':uuid', $request['uuid']);
  $updates_sql->execute();
  $updates_sql->setFetchMode(PDO::FETCH_ASSOC);
  $updates_result = $updates_sql->fetchAll();
  return $updates_result;
}

function get_subscribers($db, $request) {
  $users_stmt = "SELECT user_uuid FROM ticket_subscribers WHERE ticket_uuid=:uuid";
  $users_sql = $db->prepare($users_stmt);
  $users_sql->bindParam(':uuid', $request['uuid']);
  $users_sql->execute();
  $users_sql->setFetchMode(PDO::FETCH_ASSOC);
  $users_result = $users_sql->fetchAll();
  return $users_result;
}

function isAuthorised($user, $authorised_users, $request) {
  if ( in_array($user, $authorised_users) || $_SESSION['uuid'] == $request['created_by']) { return true; } else { return false; }
}
