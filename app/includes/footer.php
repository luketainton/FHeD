<footer class="footer mt-auto py-3">
  <div class="text-center text-muted">
    <?php
      echo($_ENV['APP_NAME'] . " " . get_version() . ".<br>");
      if ($_ENV['APP_NAME'] != "FHeD") {
          echo("Powered by FHeD.");
      };
    ?><br>
    <?php if (is_signed_in()) {
        ?>
      <a data-target="#mailingListModal" data-toggle="modal" href="#mailingListModal">Subscribe</a>
      <div class="modal fade" id="mailingListModal" tabindex="-1" aria-labelledby="mailingListModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="mailingListModalLabel">FHeD Users mailing list</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <p>
              The <i>FHeD Users</i> mailing list is aimed at administrators managing an FHeD deployment, allowing you to interact
              with other FHeD administrators and get help from the developers.
            </p>
            <p>
              <b>You need a Google account to subscribe.</b> Your data will be stored in accordance with Google's <a target="_blank" href="https://policies.google.com/privacy">privacy policy</a>.
            </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <a href='https://groups.google.com/g/fhed-users' class='btn btn-success'>Subscribe</a>
            </div>
          </div>
        </div>
      </div>
    <?php
    } ?>
  </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
