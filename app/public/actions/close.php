<?php
    $PAGE_NAME = "Close request";
    require_once __DIR__ . "/../../includes/prereqs.php";

    $request = get_request($db, $_GET['rid']);
    $authorised_users = get_subscribers($db, $request);
    if ($_SESSION['uuid'] == $request['created_by']) {
      $is_authorised = true;
    } else {
      $is_authorised = false;
    };

    // Close request
    if ($is_authorised == true) {
      try {
          $stmt = "UPDATE tickets SET status='Closed' WHERE uuid=:uuid";
          $sql = $db->prepare($stmt);
          $sql->bindParam(':uuid', $_GET['rid']);
          $sql->execute();
      } catch (PDOException $e) {
          $alert = array("danger", "Failed to close request: " . $e->getMessage());
      }
      $newURL = "/";
      echo("<script>window.location = '$newURL'</script>");
    } else {
        $alert = array("danger", "You are not authorised to close this request.");
        $newURL = "/view?rid=" . $request['uuid'];
        echo("<script>window.location = '$newURL'</script>");
    }

?>
