<?php
    $PAGE_NAME = "Close request";
    require_once __DIR__ . "/../../includes/prereqs.php";

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
              $sql->bindParam(':uuid', $request['uuid']);
              $sql->execute();
          } catch (PDOException $e) {
              $alert = array("danger", "Failed to close request: " . $e->getMessage());
          }
        }
        $newURL = "/";
        echo("<script>window.location = '$newURL'</script>");
    } else {
        $alert = array("danger", "You are not authorised to close this request.");
        $newURL = "/view?rid=$request['uuid']";
        echo("<script>window.location = '$newURL'</script>");
    }

?>
