<?php
  function get_all_users($db) {
    try {
      $stmt = "SELECT * FROM users";
      $sql = $db->prepare($stmt);
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      $result = $sql->fetchAll();
    } catch (PDOException $e) {
      echo("Error: " . $e->getMessage());
    }
    return $result;
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

  function get_my_open_requests($db) {
    $ticket_stmt = "SELECT * FROM tickets WHERE created_by=:uuid AND status != 'Closed'";
    $ticket_sql = $db->prepare($ticket_stmt);
    $ticket_sql->bindParam(':uuid', $_SESSION['uuid']);
    $ticket_sql->execute();
    $ticket_sql->setFetchMode(PDO::FETCH_ASSOC);
    $ticket_result = $ticket_sql->fetchAll();
    return $ticket_result;
  }

  function get_my_closed_requests($db) {
    $ticket_stmt = "SELECT * FROM tickets WHERE created_by=:uuid AND status = 'Closed'";
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

  function get_open_subscribed_requests($db) {
      $requests = array();
      $sub_tickets_stmt = "SELECT ticket_uuid FROM ticket_subscribers WHERE user_uuid=:uuid";
      $sub_tickets_sql = $db->prepare($sub_tickets_stmt);
      $sub_tickets_sql->bindParam(':uuid', $_SESSION['uuid']);
      $sub_tickets_sql->execute();
      $sub_tickets_sql->setFetchMode(PDO::FETCH_ASSOC);
      $sub_tickets_result = $sub_tickets_sql->fetchAll();
      foreach ($sub_tickets_result as $tkt) {
        $stmt = "SELECT * FROM tickets WHERE uuid=:uuid  AND status != 'Closed'";
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

  function get_files($db, $request) {
    $stmt = "SELECT * FROM ticket_uploads WHERE ticket=:uuid";
    $sql = $db->prepare($stmt);
    $sql->bindParam(':uuid', $request['uuid']);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $result = $sql->fetchAll();
    return $result;
  }

  function get_single_file($db, $fileid) {
    $stmt = "SELECT * FROM ticket_uploads WHERE id=:fileid";
    $sql = $db->prepare($stmt);
    $sql->bindParam(':fileid', $fileid);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $result = $sql->fetchAll();
    $file = $result[0];
    return $file;
  }

  function get_subscribers($db, $request) {
    $subs = array();
    $users_stmt = "SELECT user_uuid FROM ticket_subscribers WHERE ticket_uuid=:uuid";
    $users_sql = $db->prepare($users_stmt);
    $users_sql->bindParam(':uuid', $request['uuid']);
    $users_sql->execute();
    $users_sql->setFetchMode(PDO::FETCH_ASSOC);
    $users_result = $users_sql->fetchAll();
    foreach ($users_result as $u) {
      array_push($subs, $u['user_uuid']);
    }
    return $subs;
  }

  function isAuthorised($user, $authorised_users, $request) {
    if ( in_array($user, $authorised_users) || $_SESSION['uuid'] == $request['created_by']) { return true; } else { return false; }
  }
