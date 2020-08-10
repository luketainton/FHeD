<?php
    $PAGE_NAME = "Add subscriber";
    require_once __DIR__ . "/../../includes/prereqs.php";

    $request = get_request($db, $_POST['rid']);
    $authorised_users = get_subscribers($db, $request);
    $is_authorised = isAuthorised($_SESSION['uuid'], $authorised_users, $request);

    // Add subscriber
    if ($is_authorised == true) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
          try {
              $stmt = "INSERT INTO ticket_subscribers (ticket_uuid, user_uuid) VALUES (:tktuuid, :usruuid)";
              $sql = $db->prepare($stmt);
              $sql->bindParam(':tktuuid', $request['uuid']);
              $sql->bindParam(':usruuid', $_POST['addSubSelector']);
              $sql->execute();
          } catch (PDOException $e) {
              $alert = array("danger", "Failed to add subscriber: " . $e->getMessage());
          }
        }
        $newURL = "/editsub?rid=" . $request['uuid'];
        echo("<script>window.location = '$newURL'</script>");
    } else {
        $alert = array("danger", "You are not authorised to manage subscribers on this request.");
        $newURL = "/editsub?rid=" . $request['uuid'];
        echo("<script>window.location = '$newURL'</script>");
    }

?>
