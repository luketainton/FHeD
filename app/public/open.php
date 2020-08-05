<?php
    $PAGE_NAME = "Open requests";
    require_once __DIR__ . "/../includes/header.php";
?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <?php if (is_signed_in()) { ?>
    <section class="jumbotron text-center">
      <div class="container">
        <h1>Open requests</h1>
        <p class="lead text-muted">
          Here you can find all of your requests, and other requests that you are subscribed to.
        </p>
      </div>
    </section>

      <section>
        <p>This page is currently under construction.</p>
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
