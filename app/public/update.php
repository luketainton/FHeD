<?php
    $PAGE_NAME = "Update Request";
    require_once __DIR__ . "/../includes/header.php";

    // If form submitted, save to database
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      try {
        // Process ticket data
        $stmt = "INSERT INTO ticket_updates (ticket, user, msg) VALUES (:tktuuid, :user, :msg)";
        $sql = $db->prepare($stmt);
        $sql->bindParam(':tktuuid', $_POST['rid']);
        $sql->bindParam(':user', $_SESSION['uuid']);
        $sql->bindParam(':msg', $_POST['msg']);
        $sql->execute();
      } catch (PDOException $e) {
        // echo("Error: <br>" . $e->getMessage() . "<br>");
        $new_ticket_alert = array("danger", "Failed to save update: " . $e->getMessage());
      }
      header('Location: /view?rid=' . $_POST['rid'], true);
    } else { // Form not yet submitted
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
        $new_ticket_alert = array("danger", "Failed to get request: " . $e->getMessage());
      }

      // Get ticket updates
      try {
        $updates_stmt = "SELECT * FROM ticket_updates WHERE ticket=:uuid";
        $updates_sql = $db->prepare($updates_stmt);
        $updates_sql->bindParam(':uuid', $_GET['rid']);
        $updates_sql->execute();
        $updates_sql->setFetchMode(PDO::FETCH_ASSOC);
        $updates_result = $updates_sql->fetchAll();
      } catch (PDOException $e) {
        $new_ticket_alert = array("danger", "Failed to get updates: " . $e->getMessage());
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
        $new_ticket_alert = array("danger", "Failed to get subscribers: " . $e->getMessage());
      }
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
      <div class="container">
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
          You need to log in to access this page.
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
      </div>
    </section>
  <?php } else {
      if ($is_authorised == true) { ?>
      <section class="jumbotron text-center">
        <div class="container">
          <h1><?php echo($request['title']); ?></h1>
          <p style="color: gray; font-style: italic;"><?php echo("#" . sprintf("%'.05d\n", $request["id"])); ?></p>
          <p class="lead text-muted"><?php echo($request['description']); ?></p>
        </div>
      </section>
      <section>
        <div class="container">
          <div class="row">
            <div class="col-4">
              <div class="card mx-auto">
                <div class="card-header"><span class="mdi mdi-information-outline"></span> Information</div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <div class="container">
                      <div class="row">
                        <span style="display: inline;"><b>Status:</b></span>
                        <span style="display: inline; margin-left: 1%;"><?php echo($request['status']); ?></span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="container">
                      <div class="row">
                        <span style="display: inline;"><b>Created by:</b></span>
                        <span style="display: inline; margin-left: 1%;"><?php echo(get_user_name($db, $request['created_by'])); ?></span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="container">
                      <div class="row">
                        <span style="display: inline;"><b>Assigned to:</b></span>
                        <?php if ($request['assignee'] != null) {
                          echo("<span style='display: inline; margin-left: 1%;'>" . get_user_name($db, $request['assignee']) . "</span>");
                        } else {
                          echo("<span class='text-muted' style='display: inline; margin-left: 1%;'>None</span>");
                        } ?>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="container">
                      <div class="row">
                        <span style="display: inline;"><b>Created:</b></span>
                        <span style="display: inline; margin-left: 1%;"><?php echo($request['created_on']); ?></span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="container">
                      <div class="row">
                        <span style="display: inline;"><b>Last updated:</b></span>
                        <span style="display: inline; margin-left: 1%;"><?php echo($request['last_updated']); ?></span>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <div class="col-8">
              <div class="card mx-auto">
                <div class="card-header"><span class="mdi mdi-update"></span> Updates</div>
                <ul class="list-group list-group-flush">
                  <?php
                    if (count($updates_result) == 0) {
                      echo("<center><b>No updates</b></center>");
                    } else {
                      foreach($updates_result as $update) {
                  ?>
                    <li class="list-group-item">
                      <div class="container">
                        <div class="row">
                          <span style="display: inline;"><b><?php echo(get_user_name($db, $update['user'])); ?></b></span><span class="text-muted"><i><?php echo(" " . $update['created']); ?></i></span>
                        </div>
                        <div class="row">
                          <span><?php echo($update['msg']); ?></span>
                        </div>
                      </div>
                    </li>
                  <?php } } ?>
                </ul>
              </div>
            </div>

          </div>
        </div>
      </section>

      <section style="margin-top: 2%;">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="card mx-auto">
                <div class="card-header"><span class="mdi mdi-send-outline"></span> Post update</div>
                  <form action="/update" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <input type="hidden" id="rid" name="rid" value="b4b3d4cf-d64d-11ea-b64d-0019997c933f">
                    </div>
                    <div class="form-group" style="margin: 2%;">
                      <textarea type="text" class="form-control" id="msg" name="msg" rows="3"></textarea>
                      <button type="submit" class="btn btn-primary" style="margin-top: 2%;">Submit</button>
                      <a href="/view?rid=<?php echo($_GET['rid']); ?>" class="btn btn-danger" style="margin-top: 2%;">Cancel</a>
                    </div>
                  </form>
              </div>
            </div>
          </div>
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
