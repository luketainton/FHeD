<footer class="footer mt-auto py-3">
  <div class="text-center text-muted">
    <?php
      echo( $_ENV['APP_NAME'] . " v" . $_ENV['APP_VERSION']);
      if ($_ENV['APP_NAME'] != "FHeD") {echo(", powered by FHeD");};
    ?><br>
    <?php if (is_signed_in()) { ?>
      <form id="mailer-subscribe" method="post" action="https://mailer.tainton.uk/subscription/form" style="margin-top: -1%;">
        <div class="form-group">
          <input hidden type="text" class="form-control" id="name" name="name" value="<?php echo($_SESSION['full_name']); ?>">
          <input hidden type="text" class="form-control" id="email" name="email" value="<?php echo($_SESSION['email']); ?>">
          <input hidden type="text" class="form-control" id="bdce4" name="l" value="bdce4805-ff03-41f1-be2c-60b28c444e83">
        </div>
        <a href="#" onclick="document.getElementById('mailer-subscribe').submit();">Subscribe</a>
      </form>
    <?php } ?>
  </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
