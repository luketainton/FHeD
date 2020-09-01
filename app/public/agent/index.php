<?php
    $PAGE_NAME = "Agent Home";
    require_once __DIR__ . "/../../includes/header.php";

    if (is_signed_in()) {
        $requests = get_my_assigned_requests($db);
    }

?>


  <section>
    <?php
      if (isset($alert)) {
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
  <?php if (is_agent) { ?>
  <section class="jumbotron text-center">
      <div class="container">
        <h1>Welcome to <?php echo($_ENV['APP_NAME']); ?> Agent Area</h1>
        <p class="lead text-muted">
          Under construction
        </p>
        <?php if (is_signed_in()) { ?>
          <p>
            <a href='/open' class='btn btn-primary my-2'>View open requests</a>
            <a href='/existing' class='btn btn-secondary my-2'>View all requests</a>
          </p>
        <?php } else { ?>
          <p><b>Please log in to view requests.</b></p>
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
                    foreach ($requests as $tkt) {
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
              <?php
                    }
                } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
<?php } else { ?>
  <div class="container">
    <h1>Access Denied</h1>
    <p class="lead text-muted">
      You do not have permission to access this area.
    </p>
  </div>
<?php } ?>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
