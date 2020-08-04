<?php
    $PAGE_NAME = "Home";
    require_once __DIR__ . "/../includes/prereqs.php";
    require_once __DIR__ . "/../includes/header.php";

    if (is_signed_in()) {
      try {
        $user_tickets_stmt = "SELECT * FROM tickets WHERE uuid=:uuid";
        $user_tickets_sql = $db->prepare($user_tickets_stmt);
        $user_tickets_sql->bindParam(':uuid', $_GET['rid']);
        $user_tickets_sql->execute();
        $user_tickets_sql->setFetchMode(PDO::FETCH_ASSOC);
        $user_tickets_result = $user_tickets_sql->fetchAll();
        $request = $user_tickets_result[0];
      } catch (PDOException $e) {
        echo("Error: " . $e->getMessage());
      }
    }

?>



<!-- Begin page content -->
<main role="main" class="flex-shrink-0">

  <?php if (is_signed_in()) { ?>
    <section class="jumbotron text-center">
      <div class="container">
        <h1><?php echo($request['title']); ?></h1>
        <p style="color: gray; font-style: italic;"><?php echo("#" . sprintf("%'.05d\n", $request["id"])); ?></p>
        <p class="lead text-muted">
          <?php echo($request['description']); ?>
        </p>
        <p>
          <?php print_r($request); ?>
        </p>
      </div>
    </section>
  <?php } else { ?>

    <section class="jumbotron text-center">
      <div class="container">
        <h1>You need to be logged in to see this page.</h1>
      </div>
    </section>
  <?php } ?>

</main>

<?php
    require_once __DIR__ . "/../includes/footer.php";
?>
