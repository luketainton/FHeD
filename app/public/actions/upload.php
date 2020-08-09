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
        if(isset($_FILES['file']) && $_FILES['file']['name'] != "") {
          try {
            $file_uuid = Uuid::uuid4()->toString();
            $file_name = $_FILES['file']['name'];
            $file_size = $_FILES['file']['size'];
            $file_type = $_FILES['file']['type'];
            $file_tmp = $_FILES['file']['tmp_name'];
            move_uploaded_file($file_tmp,"/srv/attachments/".$file_name);
            $stmt = "INSERT INTO ticket_uploads (id, ticket, user, filename) VALUES (:fileuuid, :ticket, :user, :name)";
            $sql = $db->prepare($stmt);
            $sql->bindParam(':fileuuid', $file_uuid);
            $sql->bindParam(':ticket', $_POST['rid']);
            $sql->bindParam(':user', $_SESSION['uuid']);
            $sql->bindParam(':name', $file_name);
            $sql->execute();
          } catch (PDOException $e) {
            $new_ticket_alert = array("danger", "Failed to upload file: " . $e->getMessage());
          }
          header('Location: /view?rid=' . $_POST['rid'], true);
        }
      } else {
        $new_ticket_alert = array("danger", "You are not authorised to update this request.");
        header('Location: /view?rid=' . $_POST['rid'], true);
      }
    }

?>