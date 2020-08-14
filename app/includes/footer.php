<footer class="footer mt-auto py-3">
  <div class="container">
    <span class="pull-left text-muted">
      <?php
        echo( $_ENV['APP_NAME'] . " v" . $_ENV['APP_VERSION']);
        if ($_ENV['APP_NAME'] != "FHeD") {echo(", powered by FHeD");};
      ?>
    </span>
    <?php if (is_signed_in()) { ?>
      <span class="pull-right text-muted">
        <form method="post" action="https://mailer.tainton.uk/subscription/form" class="listmonk-form">
          <div class="form-group">
            <input hidden type="text" class="form-control" id="name" name="name" value="<?php echo($_SESSION['full_name']); ?>">
            <input hidden type="text" class="form-control" id="email" name="email" value="<?php echo($_SESSION['email']); ?>">
          </div>
          <button type="submit" class="btn btn-secondary">Subscribe</button>
        </form>
      </span>
    <?php } ?>
  </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
