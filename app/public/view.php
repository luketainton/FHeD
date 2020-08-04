<?php
    $PAGE_NAME = "View Request";
    require_once __DIR__ . "/../includes/prereqs.php";
    require_once __DIR__ . "/../includes/header.php";

    if (is_signed_in()) {
      // Get ticket
      try {
        $ticket_stmt = "SELECT * FROM tickets WHERE uuid=:uuid";
        $ticket_sql = $db->prepare($ticket_stmt);
        $ticket_sql->bindParam(':uuid', $_GET['rid']);
        $ticket_sql->execute();
        $ticket_sql->setFetchMode(PDO::FETCH_ASSOC);
        $ticket_result = $ticket_sql->fetchAll();
        $request = $ticket_result[0];
      } catch (PDOException $e) {
        echo("Error: " . $e->getMessage());
      }

      // Get authorised subscribers
      try {
        $users_stmt = "SELECT user_uuid FROM ticket_subscribers WHERE ticket_uuid=:uuid";
        $users_sql = $db->prepare($users_stmt);
        $users_sql->bindParam(':uuid', $_GET['rid']);
        $users_sql->execute();
        $users_sql->setFetchMode(PDO::FETCH_ASSOC);
        $users_result = $users_sql->fetchAll();
      } catch (PDOException $e) {
        echo("Error: " . $e->getMessage());
      }

      $authorised_users = array();
      foreach($users_result as $user) {
        array_push($authorised_users, $user['user_uuid']);
      }

      if (in_array($_SESSION['uuid'], $authorised_users)) {
        $is_authorised = true;
      } else {
        $is_authorised = false;
      }
    }

?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <?php if (is_signed_in() && $is_authorised == true) { ?>
    <section class="jumbotron text-center">
      <div class="container">
        <h1><?php echo($request['title']); ?></h1>
        <p style="color: gray; font-style: italic;"><?php echo("#" . sprintf("%'.05d\n", $request["id"])); ?></p>
        <p class="lead text-muted">
          <?php echo($request['description']); ?>
        </p>
        <p>
          <?php print_r($request); ?>
        </p>
        <p>
          <?php print_r($users_result) ?>
        </p>
      </div>
    </section>
  <?php } else if (is_signed_in() && $is_authorised == false) { ?>
    <section class="jumbotron text-center">
      <div class="container">
        <h1>You are not authorised to see this page.</h1>
      </div>
    </section>
  <?php } else { ?>
    <section class="jumbotron text-center">
      <div class="container">
        <h1>You need to be logged in to see this page.</h1>
      </div>
    </section>
  <?php } ?>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
