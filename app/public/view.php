<?php
    $PAGE_NAME = "View Request";
    require_once __DIR__ . "/../includes/header.php";

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

    if (in_array($_SESSION['uuid'], $authorised_users) || $_SESSION['uuid'] == $request['created_by']) {
      $is_authorised = true;
    } else {
      $is_authorised = false;
    }

?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <?php if (!is_signed_in()) { ?>
    <section>
      <div class='alert alert-danger alert-dismissible fade show' role='alert'>
        You need to log in to access this page.
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
    </section>
  <?php } else {
      if ($is_authorised == true) { ?>
      <section class="jumbotron text-center">
        <div class="container">
          <h1><?php echo($request['title']); ?></h1>
          <p style="color: gray; font-style: italic;"><?php echo("#" . sprintf("%'.05d\n", $request["id"])); ?></p>
          <p class="lead text-muted"><?php echo($request['description']); ?></p>
          <div class="container">
            <div class="row">
              <div class="col-sm">
                <div class="card mx-auto">
                  <div class="card-header"><span class="mdi mdi-information-outline"></span> Information</div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      <div class="container">
                        <div class="row">
                          <span style="display: inline;"><b>Status:</b> <?php echo($request['status']); ?></span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="col-sm">
                <div class="card mx-auto">
                  <div class="card-header"><span class="mdi mdi-update"></span> Updates</div>
                  <ul class="list-group list-group-flush">
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
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <p><?php print_r($request); ?></p>
        </div>
      </section>
    <?php } else if ($is_authorised == false) { ?>
      <section class="jumbotron text-center">
        <div class="container">
          <h1>You are not authorised to see this page.</h1>
        </div>
      </section>
    <?php } } ?>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
