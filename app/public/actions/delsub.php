<?php
    $PAGE_NAME = "Delete Subscribers";
    require_once __DIR__ . "/../../includes/prereqs.php";

    $request = get_request($db, $_POST['rid']);
    $authorised_users = get_subscribers($db, $request);
    if ($_SESSION['uuid'] == $request['created_by']) {
      $is_authorised = true;
    } else {
      $is_authorised = false;
    };

    if (!empty($_POST['delSubSelector'])) {
      $subs_to_remove = implode(",", $_POST['delSubSelector']);

      // Remove subscriber(s)
      if ($is_authorised == true) {
          if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $stmt = "DELETE FROM ticket_subscribers WHERE sub_id IN (:sublist)";
                $sql = $db->prepare($stmt);
                $sql->bindParam(':sublist', $subs_to_remove);
                $sql->execute();
            } catch (PDOException $e) {
                $alert = array("danger", "Failed to remove subscriber(s): " . $e->getMessage());
            }
          }
      } else {
          $alert = array("danger", "You are not authorised to manage subscribers on this request.");
      }

    }
    $newURL = "/editsub?rid=" . $request['uuid'];
    echo("<script>window.location = '$newURL'</script>");

?>
