<?php
    require_once __DIR__ . "/../../includes/prereqs.php";
    use Ramsey\Uuid\Uuid;

    // If form submitted, save to database
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Process ticket data
        $tkt_uuid = Uuid::uuid4()->toString();
        $stmt = "INSERT INTO tickets (uuid, title, description, created_by) VALUES (:tktuuid, :title, :description, :user)";
        $sql = $db->prepare($stmt);
        $sql->bindParam(':tktuuid', $tkt_uuid);
        $sql->bindParam(':title', $_POST['title']);
        $sql->bindParam(':description', $_POST['description']);
        $sql->bindParam(':user', $_SESSION['uuid']);
        $sql->execute();
    } catch (PDOException $e) {
        // echo("Error: <br>" . $e->getMessage() . "<br>");
        $new_ticket_alert = array("danger", "Failed to save request: " . $e->getMessage());
    }

    // If file is uploaded, process that
    if(isset($_FILES['file']) && $_FILES['file']['name'] != "") {
        try {
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $file_tmp = $_FILES['file']['tmp_name'];
        move_uploaded_file($file_tmp,"/srv/attachments/".$file_name);
        $stmt = "INSERT INTO ticket_uploads (ticket, user, filename) VALUES (:ticket, :user, :name)";
        $sql = $db->prepare($stmt);
        $sql->bindParam(':ticket', $tkt_uuid);
        $sql->bindParam(':user', $_SESSION['uuid']);
        $sql->bindParam(':name', $file_name);
        $sql->execute();
        } catch (PDOException $e) {
        // echo("Error: <br>" . $e->getMessage() . "<br>");
        $new_ticket_alert = array("danger", "Failed to upload file: " . $e->getMessage());
        }
    }

    header('Location: /view?rid=' . $tkt_uuid, true);
    }
?>
