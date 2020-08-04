<?php
    $PAGE_NAME = "New request";
    require_once __DIR__ . "/../includes/header.php";
    use Ramsey\Uuid\Uuid;

    // If form submitted, save to database
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      try {
        // Process ticket data
        $tkt_uuid = Uuid::uuid4()->toString();
        $stmt = "INSERT INTO tickets (uuid, title, description, created_by) VALUES (:tktuuid, :title, :description, :user)";
        $sql = $db->prepare($stmt);
        $sql->bindParam(':tktuuid', $tkt_uuid);
        $sql->bindParam(':title', $_POST['title']);
        $sql->bindParam(':description', $_POST['description']);
        $sql->bindParam(':user', $_SESSION['uuid']);
        $sql->execute();
      } catch (PDOException $e) {
        // echo("Error: <br>" . $e->getMessage() . "<br>");
        $new_ticket_alert = array("danger", "Failed to save request: " . $e->getMessage());
      }

      // If file is uploaded, process that
      if(isset($_FILES['file']) && $_FILES['file']['name'] != "") {
        try {
          $file_name = $_FILES['file']['name'];
          $file_size = $_FILES['file']['size'];
          $file_type = $_FILES['file']['type'];
          $file_tmp = $_FILES['file']['tmp_name'];
          move_uploaded_file($file_tmp,"/srv/attachments/".$file_name);
          $stmt = "INSERT INTO ticket_uploads (ticket, user, filename) VALUES (:ticket, :user, :name)";
          $sql = $db->prepare($stmt);
          $sql->bindParam(':ticket', $tkt_uuid);
          $sql->bindParam(':user', $_SESSION['uuid']);
          $sql->bindParam(':name', $file_name);
          $sql->execute();
        } catch (PDOException $e) {
          // echo("Error: <br>" . $e->getMessage() . "<br>");
          $new_ticket_alert = array("danger", "Failed to upload file: " . $e->getMessage());
        }
      }

      header_remove("Location");
      header('Location: /view?rid=' . $tkt_uuid);
    }

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
        <section>
          <div class='alert alert-" . $new_ticket_alert[0] . " alert-dismissible fade show' role='alert'>
            " . $new_ticket_alert[1] . "
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
        </section>
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
      <div class="card mx-auto" style="width: 50%;">
        <form style="padding: 2%" action="/new" method="post" enctype="multipart/form-data">
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
