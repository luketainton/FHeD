<?php
    $PAGE_NAME = "Update request";
    require_once __DIR__ . "/../../includes/header.php";

    $request = get_request($db, $_POST['rid']);
    $authorised_users = get_subscribers($db, $request);
    $is_authorised = isAuthorised($_SESSION['uuid'], $authorised_users, $request);

    // If form submitted, save to database
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      if ($is_authorised == true) {
        try {
          // Process ticket data
          $stmt = "INSERT INTO ticket_updates (ticket, user, msg) VALUES (:tktuuid, :user, :msg)";
          $sql = $db->prepare($stmt);
          $sql->bindParam(':tktuuid', $request['uuid']);
          $sql->bindParam(':user', $_SESSION['uuid']);
          $sql->bindParam(':msg', $_POST['msg']);
          $sql->execute();
        } catch (PDOException $e) {
          $new_ticket_alert = array("danger", "Failed to save update: " . $e->getMessage());
        }
      } else {
        $new_ticket_alert = array("danger", "You are not authorised to update this request.");
        header('Location: /view?rid=' . $request['uuid'], true);
      }
    }

?>
