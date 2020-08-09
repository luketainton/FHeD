<?php
    $PAGE_NAME = "Upload file to request";
    require_once __DIR__ . "/../../includes/prereqs.php";
    use Ramsey\Uuid\Uuid;

    $request = get_request($db, $_POST['rid']);
    $authorised_users = get_subscribers($db, $request);
    $is_authorised = isAuthorised($_SESSION['uuid'], $authorised_users, $request);

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
            move_uploaded_file($file_tmp,$_ENV['ATTACHMENTS_PATH']."/".$file_uuid);
            $stmt = "INSERT INTO ticket_uploads (id, ticket, user, filename) VALUES (:fileuuid, :ticket, :user, :name)";
            $sql = $db->prepare($stmt);
            $sql->bindParam(':fileuuid', $file_uuid);
            $sql->bindParam(':ticket', $request['uuid']);
            $sql->bindParam(':user', $_SESSION['uuid']);
            $sql->bindParam(':name', $file_name);
            $sql->execute();
          } catch (PDOException $e) {
            $new_ticket_alert = array("danger", "Failed to upload file: " . $e->getMessage());
          }
          $newURL = "/view?rid=" . $request['uuid'];
          echo("<script>window.location = '$newURL'</script>");
        }
      } else {
        $alert = array("danger", "You are not authorised to update this request.");
        $newURL = "/view?rid=" . $request['uuid'];
        echo("<script>window.location = '$newURL'</script>");
      }
    }

?>
