<?php
    $PAGE_NAME = "Delete subscribers";
    require_once __DIR__ . "/../../includes/prereqs.php";

    $request = get_request($db, $_POST['rid']);
    $authorised_users = get_subscribers($db, $request);
    $is_authorised = isAuthorised($_SESSION['uuid'], $authorised_users, $request);

    $subs_to_remove = implode(",", $_POST['delSubSelector']);

    // Remove subscriber(s)
    if ($is_authorised == true) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
          try {
              $stmt = "DELETE FROM ticket_subscribers WHERE WHERE sub_id IN (:sublist)";
              $sql = $db->prepare($stmt);
              $sql->bindParam(':sublist', $subs_to_remove);
              $sql->execute();
          } catch (PDOException $e) {
              $alert = array("danger", "Failed to remove subscriber(s): " . $e->getMessage());
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
