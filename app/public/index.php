<?php
    $PAGE_NAME = "Home";
    require_once __DIR__ . "/../includes/header.php";

    if (is_signed_in()) {
      $requests = get_my_open_requests($db);
      $subscriptions = get_open_subscribed_requests($db);
    }

?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <section>
    <?php
      if(isset($alert)) {
        echo("
        <div class='container'>
          <div class='alert alert-" . $alert[0] . " alert-dismissible fade show' role='alert'>
            " . $alert[1] . "
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
        </div>
      ");
      unset($alert);
      }
    ?>
  </section>

  <section class="jumbotron text-center">
    <div class="container">
      <h1>Welcome to <?php echo($_ENV['APP_NAME']); ?></h1>
      <p class="lead text-muted">
        <?php
          if ($_ENV['APP_NAME'] == "FHeD") {echo("The Free HelpDesk");} else {echo($_ENV['APP_NAME']);};
        ?>
        is the one-stop shop for all of your IT-related needs. Let us know how we can help you by opening a request.
      </p>
      <?php if (is_signed_in()) { ?>
        <p>
          <a href='/new' class='btn btn-primary my-2'>Create a request</a>
          <a href='/existing' class='btn btn-secondary my-2'>View existing requests</a>
        </p>
      <?php } else { ?>
        <p><b>Please log in to create or view requests.</b></p>
      <?php } ?>
    </div>
  </section>

  <?php if (is_signed_in()) { ?>
    <div class="container" style="margin-top: -5%">
      <div class="row">
        <div class="col-sm">
          <div class="card mx-auto">
            <div class="card-header">
              <span class="mdi mdi-ticket-outline"></span> My Open Requests
            </div>
            <ul class="list-group list-group-flush">
              <?php
                if (count($requests) == 0) {
                  echo("<center><b>No open tickets</b></center>");
                } else {
                  foreach($requests as $tkt) {
              ?>
              <li class="list-group-item">
                <div class="container">
                  <div class="row">
                    <div class="col-10">
                      <span class="requestinfo text-muted">#<?php echo(sprintf("%'.05d\n", $tkt["id"])); ?> </span><span><b><?php echo($tkt['title']); ?></b></span>
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
        </div>

        <div class="col-sm">
          <div class="card mx-auto">
            <div class="card-header">
              <span class="mdi mdi-rss"></span> My Subscribed Requests
            </div>
            <ul class="list-group list-group-flush">
              <?php
                if (count($subscriptions) == 0) {
                  echo("<center><b>No subscribed tickets</b></center>");
                } else {
                  foreach($subscriptions as $sub) { ?>
              <li class="list-group-item">
                <div class="container">
                  <div class="row">
                    <div class="col-10">
                      <span class="requestinfo text-muted">#<?php echo sprintf("%'.05d\n", $sub["id"]); ?> </span><span><b><?php echo($sub['title']); ?></b></span> <span style="display: inline;" class="text-muted"><?php echo("(Creator: " . get_user_name($db, $sub['created_by']) . ")"); ?></span>
                      <p class="m-0"><?php echo($sub['description']); ?></p>
                    </div>
                    <div class="col-2">
                      <a class="btn btn-success float-right" href="view?rid=<?php echo($sub["uuid"]); ?>" role="button">Go</a>
                    </div>
                  </div>
                </div>
              </li>
              <?php } } ?>
            </ul>
          </div>
        </section>
        </div>
      </div>
    </div>
  <?php } ?>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
