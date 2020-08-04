<footer class="footer mt-auto py-3">
  <div class="container">
    <span class="text-muted">
      <?php
        echo( $_ENV['APP_NAME'] . " v" . $_ENV['APP_VERSION']);
        if ($_ENV['APP_NAME'] != "FHeD") {echo(", powered by FHeD");};
      ?>
    </span>
  </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
