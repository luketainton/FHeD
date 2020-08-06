<?php
    $PAGE_NAME = "Existing requests";
    require_once __DIR__ . "/../includes/prereqs.php";
    require_once __DIR__ . "/../includes/header.php";

    if (is_signed_in()) {
      // Get user's open tickets
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

      // Get user's closed tickets
      try {
        $closed_tickets_stmt = "SELECT uuid, id, title, description, status FROM tickets WHERE created_by=:uuid AND status='closed'#";
        $closed_tickets_sql = $db->prepare($user_tickets_stmt);
        $closed_tickets_sql->bindParam(':uuid', $_SESSION['uuid']);
        $closed_tickets_sql->execute();
        $closed_tickets_sql->setFetchMode(PDO::FETCH_ASSOC);
        $closed_tickets_result = $user_tickets_sql->fetchAll();
      } catch (PDOException $e) {
        echo("Error: " . $e->getMessage());
      }

      }
?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <?php if (is_signed_in()) { ?>
    <section class="jumbotron text-center">
      <div class="container">
        <h1>Open requests</h1>
        <p class="lead text-muted">
          Here you can find all of your requests, and other requests that you are subscribed to.
        </p>
      </div>
    </section>

      <section>
        <p>This page is currently under construction.</p>
      </section>
    <?php } else { ?>
      <section>
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
          You need to log in to access this page.
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
      </section>
    <?php } ?>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>











<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <section class="jumbotron text-center">
    <div class="container">
      <h1>Open requests</h1>
      <p class="lead text-muted">
        Here you can find all of your requests, and other requests that you are subscribed to.
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

      <section>
        <div class="card mx-auto" style="width: 80%;">
          <div class="card-header">
            <span class="mdi mdi-ticket-outline"></span> My Closed Requests
          </div>
          <ul class="list-group list-group-flush">
            <?php
              if (count($closed_tickets_result) == 0) {
                echo("<center><b>No closed tickets</b></center>");
              } else {
                foreach($closed_tickets_result as $tkt) {
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
    <?php } else { ?>
      <section>
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
          You need to log in to access this page.
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
      </section>
    <?php } ?>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
