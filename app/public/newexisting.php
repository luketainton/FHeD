<?php
    $PAGE_NAME = "Existing requests";
    require_once __DIR__ . "/../includes/header.php";

    if (is_signed_in()) {
      $open_requests = get_my_open_requests($db);
      $closed_requests = get_my_closed_requests($db);
      $open_subscriptions = get_open_subscribed_requests($db);
      $closed_subscriptions = get_closed_subscribed_requests($db);
    }
?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <section class="jumbotron text-center">
    <div class="container">
      <h1>Existing requests</h1>
      <p class="lead text-muted">
        Here you can find all of your requests, and other requests that you are subscribed to.
      </p>
    </div>
  </section>
  <?php if (is_signed_in()) { ?>
    <section>
      <div class="card mx-auto" style="width: 80%;margin-bottom: 50px;">
        <div class="card-header">
          <span class="mdi mdi-ticket-outline"></span> My Requests
        </div>
        <ul class="nav nav-pills">
          <li class="active"><a data-toggle="pill" href="#open">Open Requests</a></li>
          <li><a data-toggle="pill" href="#closed">Closed Requests</a></li>
        </ul>
        <!-- Open requests content -->
        <div id="open" class="tab-pane fade in active">
            <div class="tab-content">
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
                           <span class="requestinfo text-muted">#<?php echo(sprintf("%'.05d\n", $tkt["id"])); ?> </span>
                           <span><b><?php echo($tkt['title']); ?></b></span>
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
             <!-- Closed requests content -->
             <div id="closed" class="tab-pane fade">
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
           </section>
      <section>
        <div class="col-sm">
          <div class="card mx-auto" style="width: 80%; margin-bottom: 50px;">
            <div class="card-header">
              <span class="mdi mdi-rss"></span> My Subscribed Requests
            </div>
            <ul class="nav nav-pills">
              <li class="active"><a data-toggle="pill" href="#opensubs">Open Subscribed Requests</a></li>
              <li><a data-toggle="pill" href="#closedsubs">Closed Subscribed Requests</a></li>
            </ul>
            <!-- Open Subscribed requests content -->
            <div id="open" class="tab-pane fade in active">
                <div class="tab-content">
            <ul class="list-group list-group-flush">
              <?php
                if (count($open_subscriptions) == 0) {
                  echo("<center><b>No subscribed tickets</b></center>");
                } else {
                  foreach($open_subscriptions as $sub) { ?>
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
          </div>
          <div id="closed" class="tab-pane fade in active">
              <div class="tab-content">
                <ul class="list-group list-group-flush">
                  <?php
                    if (count($closed_subscriptions) == 0) {
                      echo("<center><b>No subscribed tickets</b></center>");
                    } else {
                      foreach($closed_subscriptions as $sub) { ?>
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
          </div>
          </div>
        </section>
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
