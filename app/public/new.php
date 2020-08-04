<?php
    $PAGE_NAME = "New request";
    require_once __DIR__ . "/../includes/prereqs.php";
    require_once __DIR__ . "/../includes/header.php";

    if (!is_signed_in()) {
      header('Location: /login');
    }
?>

<link rel="stylesheet" href="fonts/fontawesome-all.min.css">
<link rel="stylesheet" href="css/Contact-Form-Clean.css">
<link rel="stylesheet" href="css/Custom-File-Upload.css">
<link rel="stylesheet" href="css/styles.css">

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
      <div class="contact-clean">
          <form method="post">
              <div class="form-group"><input class="form-control" type="text" name="subject" placeholder="Subject"></div>
              <div class="form-group"><textarea class="form-control" name="message" placeholder="Message" rows="14"></textarea></div>
              <div class="form-group"><input type="file" id="user_group_logo" class="custom-file-input" accept="*" name="user_group_logo"><label id="user_group_label" for="user_group_logo"><i class="fas fa-upload"></i>&nbsp;Choose file(s)...</label>
                  <div class="text-center mt-2"><button class="btn btn-primary" type="submit">Upload</button></div>
                  <div class="text-center mt-2"><button class="btn btn-primary" type="submit">send </button></div>
              </div>
          </form>
      </div>
      <script src="assets/js/Custom-File-Upload.js"></script>
    </section>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
