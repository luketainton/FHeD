<?php
    $PAGE_NAME = "View Request";
    require_once __DIR__ . "/../includes/header.php";

    $request = get_request($db, $_GET['rid']);
    $updates = get_updates($db, $request);
    $files = get_files($db, $request);
    $authorised_users = get_subscribers($db, $request);
    $is_authorised = isAuthorised($_SESSION['uuid'], $authorised_users, $request);
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
        <div class="container-fluid">
          <h1><?php echo($request['title']); ?></h1>
          <p style="color: gray; font-style: italic;"><?php echo("#" . sprintf("%'.05d\n", $request["id"])); ?></p>
          <p class="lead text-muted"><?php echo($request['description']); ?></p>
            <p>
              <?php if ($_SESSION['uuid'] == $request['created_by']) { ?>
                <a href='/editsub?rid=<?php echo($request["uuid"]); ?>' class='btn btn-secondary my-2'>Manage subscribers</a>
              <?php } ?>
              <?php if ($request['status'] != 'Closed') { ?>
                <a href='/update?rid=<?php echo($request["uuid"]); ?>' class='btn btn-primary my-2'>Update the request</a>
                <a href='/upload?rid=<?php echo($request["uuid"]); ?>' class='btn btn-primary my-2'>Add attachment(s)</a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#closeModal">Close the request</button>
              <?php } ?>
            </p>
        </div>

        <div class="modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="closeModalLabel">Close the request?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Are you sure you want to close request <b><?php echo($request['title']); ?></b>? This action is irreversible.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href='/actions/close?rid=<?php echo($request["uuid"]); ?>' class='btn btn-danger'>Close request</a>
              </div>
            </div>
          </div>
        </div>

      </section>
      <section>
        <div class="container-fluid">
          <div class="row">
            <div class="col-3">
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

            <div class="col-3">
              <div class="card mx-auto">
                  <div class="card-header"><span class="mdi mdi-file-document-outline"></span> Files</div>
                  <ul class="list-group list-group-flush">
                    <?php
                      if (count($files) == 0) {
                        echo("<center><b>No files uploaded</b></center>");
                      } else {
                        foreach($files as $file) {
                    ?>
                      <li class="list-group-item">
                        <div class="container">
                          <div class="row">
                            <span style="display: inline;"><b><?php echo(get_user_name($db, $file['user'])); ?></b></span><span class="text-muted"><i> <?php echo(" " . $file['created']); ?></i></span>
                          </div>
                          <div class="row">
                            <a target="_blank" href="<?php echo('/actions/download?file=' . $file['id']); ?>"><span><?php echo($file['filename']); ?></span></a>
                          </div>
                        </div>
                      </li>
                    <?php } } ?>
                  </ul>
                </div>
              </div>

            <div class="col-6">
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
                          <span style="display: inline;"><b><?php echo(get_user_name($db, $update['user'])); ?></b></span><span class="text-muted"><i> <?php echo(" " . $update['created']); ?></i></span>
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
