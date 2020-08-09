<?php
    require_once __DIR__ . "/../../includes/header.php";

    // Get authorised subscribers
    try {
      $users_stmt = "SELECT user_uuid FROM ticket_subscribers WHERE ticket_uuid=:uuid";
      $users_sql = $db->prepare($users_stmt);
      $users_sql->bindParam(':uuid', $_GET['rid']);
      $users_sql->execute();
      $users_sql->setFetchMode(PDO::FETCH_ASSOC);
      $users_result = $users_sql->fetchAll();
    } catch (PDOException $e) {
      $new_ticket_alert = array("danger", "Failed to get subscribers: " . $e->getMessage());
    }


    $authorised_users = array();
    foreach($users_result as $user) {
      array_push($authorised_users, $user['user_uuid']);
    }

    if (in_array($_SESSION['uuid'], $authorised_users) || $_SESSION['uuid'] == $request['created_by']) {
      $is_authorised = true;
    } else {
      $is_authorised = false;
    }

    // If form submitted, save to database
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      if ($is_authorised == true) {
        try {
          // Process ticket data
          $stmt = "INSERT INTO ticket_updates (ticket, user, msg) VALUES (:tktuuid, :user, :msg)";
          $sql = $db->prepare($stmt);
          $sql->bindParam(':tktuuid', $_POST['rid']);
          $sql->bindParam(':user', $_SESSION['uuid']);
          $sql->bindParam(':msg', $_POST['msg']);
          $sql->execute();
        } catch (PDOException $e) {
          $new_ticket_alert = array("danger", "Failed to save update: " . $e->getMessage());
        }
      } else {
        $new_ticket_alert = array("danger", "You are not authorised to update this request.");
        header('Location: /view?rid=' . $_POST['rid'], true);
      }
    }

?>