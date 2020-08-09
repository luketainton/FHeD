<?php
    $PAGE_NAME = "New request";
    require_once __DIR__ . "/../includes/header.php";

    if (!is_signed_in()) {
      $new_ticket_alert = array("danger", "You need to log in to access this page.");
    }
?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <section>
    <?php
      if(isset($new_ticket_alert)) {
        echo("
        <div class='container'>
          <div class='alert alert-" . $new_ticket_alert[0] . " alert-dismissible fade show' role='alert'>
            " . $new_ticket_alert[1] . "
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
        </div>
      ");
      unset($new_ticket_alert);
      }
    ?>
  </section>

  <?php if (is_signed_in()) { ?>
    <section class="jumbotron text-center">
      <div class="container">
        <h1>Create a new request</h1>
        <p class="lead text-muted">
          Fill in the form below to create a new request. We'll respond to it as soon as we can.
        </p>
      </div>
    </section>

    <section>
      <div class="card mx-auto" style="width: 50%;margin-bottom: 50px;">
        <form style="padding: 2%" action="/actions/create" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="title">Title: </label>
            <input type="text" class="form-control" id="title" name="title">
          </div>
          <div class="form-group">
            <label for="description">Description: </label>
            <textarea type="text" class="form-control" id="description" name="description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="file">Upload file(s): </label>
            <input type="file" class="form-control-file" id="file" name="file">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </section>
  <?php } ?>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
