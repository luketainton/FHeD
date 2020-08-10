<?php
    $PAGE_NAME = "Manage request subscribers";
    require_once __DIR__ . "/../includes/header.php";

    $request = get_request($db, $_GET['rid']);
    $authorised_users = get_subscribers($db, $request);
    $is_authorised = isAuthorised($_SESSION['uuid'], $authorised_users, $request);

    $all_users = get_all_users($db);

    function get_req_subs($db, $uuid) {
      $stmt = "SELECT * FROM ticket_subscribers WHERE ticket_uuid=:uuid";
      $sql = $db->prepare($stmt);
      $sql->bindParam(':uuid', $uuid);
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      $result = $sql->fetchAll();
      return $result;
    }

    $subs = get_req_subs($db, $request['uuid'])
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
                <div class="card-header"><span class="mdi mdi-information-outline">
                  </span> Information
                </div>
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
                <div class="card-header">
                  <span class="mdi mdi-rss"></span> Manage Subscribers
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <div class="container">
                      <div class="row">
                        <form method="post" action="/actions/delsub">
                          <div class="form-group">
                            <input type="hidden" id="rid" name="rid" value="<?php echo($request['uuid']); ?>">
                            <label for="delSubSelector">Remove subscribers:</label>
                            <select multiple class="form-control" id="delSubSelector" name="delSubSelector">
                              <?php foreach($subs as $sub) {
                                  echo("<option value='" . $sub['sub_id'] . "'>" . get_user_name($db, $sub['user_uuid']) . "</option>");
                                } ?>
                            </select>
                          </div>
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </form>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="container">
                      <div class="row">
                        <form method="post" action="/actions/addsub">
                          <div class="form-group">
                            <input type="hidden" id="rid" name="rid" value="<?php echo($request['uuid']); ?>">
                            <label for="addSubSelector">Add subscriber:</label>
                            <select class="form-control" id="addSubSelector" name="addSubSelector">
                              <?php foreach($all_users as $usr) {
                                if (!in_array($usr['uuid'], $authorised_users) && $usr['uuid'] != $request['created_by'] && $usr['uid'] != "system") {
                                  echo("<option value='" . $usr['uuid'] . "'>" . get_user_name($db, $usr['uuid']) . "</option>");
                                }
                              } ?>
                            </select>
                          </div>
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                      </div>
                    </div>
                  </li>
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
