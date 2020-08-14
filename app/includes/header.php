<?php
    require_once __DIR__ . "/prereqs.php";
    $PAGE_TITLE = $PAGE_NAME . " :: " . $_ENV['APP_NAME'];
?>

<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#563d7c">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title><?php echo( $PAGE_TITLE ); ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.3.45/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/css/custom.css">
  </head>

  <body class="d-flex flex-column h-100">
    <header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="/"><?php echo($_ENV['APP_NAME']); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link <?php if (!is_signed_in()) {echo(' disabled');} ?>" href="/new">New request</a>
                </li>
                <li class="nav-item">
                <a class="nav-link <?php if (!is_signed_in()) {echo(' disabled');} ?>" href="/existing">Existing requests</a>
                </li>
            </ul>
        <div class="mt-2 mt-md-0">
          <ul class="navbar-nav">
            <?php
              if (is_signed_in()) { ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo($_SESSION['full_name']); ?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item text-muted" href="#"><?php echo($_SESSION['username']); ?></a>
                    <a class="dropdown-item" href="<?php echo($_ENV['OIDC_HOST'] . "/account?referrer=" . $_ENV['OIDC_CLIENT_ID'] . "&referrer_uri=" . urlencode($_ENV['APP_URL'])); ?>">Profile</a>
                    <!-- <div class="dropdown-divider"></div> -->
                    <a class="dropdown-item" href="/actions/logout">Log out</a>
                  </div>
                </li>
              <?php } else { ?>
                <li class="nav-item">
                  <a class="nav-link" href="/actions/login">Log in</a>
                </li>
              <?php } ?>
            </ul>
        </div>
        </div>
    </nav>
    </header>
