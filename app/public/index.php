<?php
    $PAGE_NAME = "Home";
    require_once __DIR__ . "/../includes/prereqs.php";
    require_once __DIR__ . "/../includes/header.php";

    if (is_signed_in()) {
      // Get user's own tickets
      try {
        $user_tickets_stmt = "SELECT uuid, id, title, description, status FROM tickets WHERE created_by=:uuid";
        $user_tickets_sql = $db->prepare($user_tickets_stmt);
        $user_tickets_sql->bindParam(':uuid', $_SESSION['uuid']);
        $user_tickets_sql->execute();
        $user_tickets_sql->setFetchMode(PDO::FETCH_ASSOC);
        $user_tickets_result = $user_tickets_sql->fetchAll();
      } catch (PDOException $e) {
        echo("Error: " . $e->getMessage());
      }

      // Get tickets user has subscribed to
      try {
        $sub_tickets_stmt = "SELECT ticket_uuid FROM ticket_subscribers WHERE user_uuid=:uuid";
        $sub_tickets_sql = $db->prepare($sub_tickets_stmt);
        $sub_tickets_sql->bindParam(':uuid', $_SESSION['uuid']);
        $sub_tickets_sql->execute();
        $sub_tickets_sql->setFetchMode(PDO::FETCH_ASSOC);
        $sub_tickets_result = $sub_tickets_sql->fetchAll();
      } catch (PDOException $e) {
        echo("Error: " . $e->getMessage());
      }

      $user_tickets_sub = 0; // Force 'no subbed tickets' msg until the code works
    }
?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <section class="jumbotron text-center">
    <div class="container">
      <h1>Welcome to <?php echo($_ENV['APP_NAME']); ?></h1>
      <p class="lead text-muted">
        <?php
          if ($_ENV['APP_NAME'] == "FHeD") {echo("The Free HelpDesk");} else {echo($_ENV['APP_NAME']);};
        ?>
        is the one-stop shop for all of your IT-related needs. Let us know how we can help you by opening a request.
      </p>
      <p>
        <?php
          if (is_signed_in()) {
            echo("
              <a href='/new' class='btn btn-primary my-2'>Create a request</a>
              <a href='/open' class='btn btn-secondary my-2'>View existing requests</a>
            ");
          } else {
            echo("<b>Please log in to create or view tickets.</b>");
          }
        ?>
      </p>
    </div>
  </section>

  <?php if (is_signed_in()) { ?>
    <section>
      <div class="card mx-auto" style="width: 80%;">
        <div class="card-header">
          <span class="mdi mdi-ticket-outline"></span> My Open Requests
        </div>
        <ul class="list-group list-group-flush">
          <?php
            if (count($user_tickets_result) == 0) {
              echo("<center><b>No open tickets</b></center>");
            } else {
              foreach($user_tickets_result as $tkt) {
          ?>
          <li class="list-group-item">
            <div class="container">
              <div class="row">
                <div class="col-10">
                  <span style="display: inline;" class="text-muted">#<?php echo(sprintf("%'.05d\n", $tkt["id"])); ?> </span><span><b><?php echo($tkt['title']); ?></b></span>
                  <p class="m-0"><?php echo($tkt['description']); ?></p>
                </div>
                <div class="col-2">
                  <a class="btn btn-success float-right" href="view?rid=<?php echo($tkt["uuid"]); ?>" role="button">Go</a>
                </div>
              </div>
            </div>
          </li>
          <?php } } ?>
        </ul>
      </div>
    </section>

    <section style="margin-top: 1%;">
      <div class="card mx-auto" style="width: 80%;">
        <div class="card-header">
          <span class="mdi mdi-rss"></span> My Subscribed Requests
        </div>
        <ul class="list-group list-group-flush">
          <?php
            if ($user_tickets_sub == 0) {
            // if (count($sub_tickets_result) == 0) {
              echo("<center><b>No subscribed tickets</b></center>");
            } else {
              foreach($sub_tickets_result as $tkt) {
          ?>
          <li class="list-group-item">
            <div class="container">
              <div class="row">
                <div class="col-10">
                  <span style="display: inline;" class="text-muted">#<?php echo sprintf("%'.05d\n", $tkt["id"]); ?> </span><span><b><?php echo($tkt['title']); ?></b></span>
                  <p class="m-0"><?php echo($tkt['description']); ?></p>
                </div>
                <div class="col-2">
                  <a class="btn btn-success float-right" href="view?rid=<?php echo($tkt["uuid"]); ?>" role="button">Go</a>
                </div>
              </div>
            </div>
          </li>
          <?php } } ?>
        </ul>
      </div>
    </section>
  <?php } ?>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
