<?php
    $PAGE_NAME = "Logging out...";
    require_once __DIR__ . "/../../includes/prereqs.php";

    $access_token = $_SESSION['access_token'];
    session_destroy();
    $oidc->signOut($access_token, $_ENV['APP_URL']);
?>
