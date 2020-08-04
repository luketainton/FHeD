<?php
    $PAGE_NAME = "Logging out...";
    require_once __DIR__ . "/../includes/prereqs.php";

    session_destroy();

    header('Location: /');
?>
