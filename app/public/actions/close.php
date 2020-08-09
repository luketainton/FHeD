<?php
    $PAGE_NAME = "Close request";
    require_once __DIR__ . "/../../includes/header.php";

    $request = get_request($db, $_GET['rid']);
    $authorised_users = get_subscribers($db, $request);
    $is_authorised = isAuthorised($_SESSION['uuid'], $authorised_users, $request);

    // Close request
    if ($is_authorised == true) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            // Process ticket data
            $stmt = "UPDATE tickets SET status = 'Closed' WHERE uuid=:uuid";
            $sql = $db->prepare($stmt);
            $sql->bindParam(':uuid', $_POST['rid']);
            $sql->execute();
        } catch (PDOException $e) {
            $new_ticket_alert = array("danger", "Failed to close request: " . $e->getMessage());
        }
        header('Location: /', true);
        }
    } else {
        $new_ticket_alert = array("danger", "You are not authorised to close this request.");
        header('Location: /view?rid=' . $request['uuid'], true);
    }

?>
