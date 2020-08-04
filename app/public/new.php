<?php
    $PAGE_NAME = "New request";
    require_once __DIR__ . "/../includes/header.php";

    // If form submitted, save to database
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      try {
        // Process ticket data
        $stmt = "INSERT INTO tickets (title, description, created_by) VALUES (:title, :description, :user)";
        $sql = $db->prepare($stmt);
        $sql->bindParam(':title', $_POST['title']);
        $sql->bindParam(':description', $_POST['description']);
        $sql->bindParam(':user', $_SESSION['uuid']);
        $sql->execute();
      } catch (PDOException $e) {
        // echo("Error: <br>" . $e->getMessage() . "<br>");
        $new_ticket_alert = array("danger", "SQL Error: " . $e->getMessage());
      }

        // Get ticket UUID
        try {
          $tkt_stmt = "SELECT uuid FROM tickets WHERE created_by=:uuid AND created_on > date_sub(now(), interval 1 minute)";
          $tkt_sql = $db->prepare($tkt_stmt);
          $tkt_sql->bindParam(':uuid', $_SESSION['uuid']);
          $tkt_sql->execute();
          $tkt_sql->setFetchMode(PDO::FETCH_ASSOC);
          $tkt_result = $tkt_sql->fetchAll()[0];
          $tkt_uuid = $tkt_result['uuid'];
        } catch (PDOException $e) {
          // echo("Error: <br>" . $e->getMessage() . "<br>");
          $new_ticket_alert = array("danger", "SQL Error: " . $e->getMessage());
        }

        // If file is uploaded, process that
        if(isset($_FILES['file'])) {
          try {
            $file_name = $_FILES['file']['name'];
            $file_size =$_FILES['file']['size'];
            $file_type=$_FILES['file']['type'];
            $file_tmp =$_FILES['file']['tmp_name'];
            move_uploaded_file($file_tmp,"/srv/attachments/".$file_name);
            $stmt = "INSERT INTO ticket_uploads (ticket, user, path) VALUES (:ticket, :user, :filepath)";
            $sql = $db->prepare($stmt);
            $sql->bindParam(':ticket', $tkt_uuid);
            $sql->bindParam(':user', $_SESSION['uuid']);
            $sql->bindParam(':filepath', "/srv/attachments/".$file_name);
            $sql->execute();
          } catch (PDOException $e) {
            // echo("Error: <br>" . $e->getMessage() . "<br>");
            $new_ticket_alert = array("danger", "SQL Error: " . $e->getMessage());
          }
        }


      header_remove("Location");
      header('Location: /view?rid=' . $tkt_uuid);
    }
?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <section>
    <?php
      if(isset($new_ticket_alert)) {
        echo("
        <div class='alert alert-" . $new_ticket_alert[0] . "' role='alert'>
          " . $new_ticket_alert[1] . "
        </div>
      "); }
    ?>
  </section>

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

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
