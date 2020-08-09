<?php
    $PAGE_NAME = "Existing requests";
    require_once __DIR__ . "/../includes/prereqs.php";
    require_once __DIR__ . "/../includes/header.php";

    if (is_signed_in()) {
      $open_requests = array();
      $closed_requests = array();

      $requests = get_my_requests($db);

      foreach($requests as $req) {
        if ($req['status'] != "Closed") {
          array_push($open_requests, $req);
        } elseif ($req['status'] == "Closed") {
          array_push($closed_requests, $req);
        }
      }
    }
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
      <div class="card mx-auto" style="width: 80%;margin-bottom: 50px;">
        <div class="card-header">
          <span class="mdi mdi-ticket-outline"></span> My Open Requests
        </div>
        <ul class="list-group list-group-flush">
          <?php
            if (count($open_requests) == 0) {
              echo("<center><b>No open tickets</b></center>");
            } else {
              foreach($open_requests as $tkt) {
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
        <div class="card mx-auto" style="width: 80%;;margin-bottom: 50px;">
          <div class="card-header">
            <span class="mdi mdi-ticket-outline"></span> My Closed Requests
          </div>
          <ul class="list-group list-group-flush">
            <?php
              if (count($closed_requests) == 0) {
                echo("<center><b>No closed tickets</b></center>");
              } else {
                foreach($closed_requests as $tkt) {
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
