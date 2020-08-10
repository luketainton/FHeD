<?php
    $PAGE_NAME = "Upload file";
    require_once __DIR__ . "/../includes/header.php";

    $request = get_request($db, $_GET['rid']);
    $updates = get_updates($db, $request);
    $authorised_users = get_subscribers($db, $request);
    $is_authorised = isAuthorised($_SESSION['uuid'], $authorised_users, $request);
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
                    if (count($updates) == 0) {
                      echo("<center><b>No updates</b></center>");
                    } else {
                      foreach($updates as $update) {
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
        <div class="col-sm">
          <div class="card mx-auto">
            <div class="card-header">
              <span class="mdi mdi-rss"></span> Request Subscribers
            </div>
            <ul class="list-group list-group-flush">
              <?php
                if (count($subscribers) == 0) {
                  echo("<center><b>No subscribers</b></center>");
                } else {
                  foreach($subscribers as $sub) { ?>
              <li class="list-group-item">
                <div class="container">
                  <div class="row">
                    <div class="col-10">
                      <span style="display: inline;" class="text-muted">#<?php echo sprintf("%'.05d\n", $sub["id"]); ?> </span><span><b><?php echo($sub['title']); ?></b></span> <span style="display: inline;" class="text-muted"><?php echo("(Creator: " . get_user_name($db, $sub['created_by']) . ")"); ?></span>
                      <p class="m-0"><?php echo($sub['description']); ?></p>
                    </div>
                    <div class="col-2">
                      <a class="btn btn-success float-right" href="view?rid=<?php echo($sub["uuid"]); ?>" role="button">Edit</a>
                      <a class="btn btn-success float-right" href="view?rid=<?php echo($sub["uuid"]); ?>" role="button">Delete</a>
                    </div>
                  </div>
                </div>
              </li>
              <?php } } ?>
            </ul>
          </div>
        </section>
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
