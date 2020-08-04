<?php
    $PAGE_NAME = "New request";
    require_once __DIR__ . "/../includes/prereqs.php";
    require_once __DIR__ . "/../includes/header.php";

    if (!is_signed_in()) {
      header('Location: /login');
    }
?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <section class="jumbotron text-center">
    <div class="container">
      <h1>Create a new request</h1>
      <p class="lead text-muted">
        Fill in the form below to create a new request. We'll respond to it as soon as we can.
      </p>
    </div>
  </section>

    <section>
      <p>This page is currently under construction.</p>
    </section>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
