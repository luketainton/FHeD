<?php
    $PAGE_NAME = "Logging in...";
    require_once __DIR__ . "/../../includes/prereqs.php";

    // Perform the OIDC authentication
    try {
      $oidc->authenticate();
      $_SESSION['access_token'] = $oidc->requestClientCredentialsToken()->access_token;
      $oidc_user = array(
        'sub' => $oidc->requestUserInfo('sub'),
        'username' => $oidc->requestUserInfo('preferred_username'),
        'given_name' => $oidc->requestUserInfo('given_name'),
        'family_name' => $oidc->requestUserInfo('family_name'),
        'email' => $oidc->requestUserInfo('email'),
      );
    } catch (Jumbojett\OpenIDConnectClientException $e) {
      $alert = array("danger", "Error during OpenID Connect authentication: " . $e->getMessage());
    }

    if (!user_exists($db, $uuid))
    {
      // User doesn't already exist
      try {
        $stmt = "INSERT INTO users (uuid, uid, given_name, family_name, email) VALUES (:sub, :username, :given, :family, :email)";
        $sql = $db->prepare($stmt);
        $sql->bindParam(':sub', $oidc_user['sub']);
        $sql->bindParam(':username', $oidc_user['username']);
        $sql->bindParam(':given', $oidc_user['given_name']);
        $sql->bindParam(':family', $oidc_user['family_name']);
        $sql->bindParam(':email', $oidc_user['email']);
        $sql->execute();
      } catch (Jumbojett\PDOException $e) {
        echo("Error during creation of new user record: " . $e->getMessage());
        die();
        $alert = array("danger", "Error during creation of new user record: " . $e->getMessage());
      }
    } else {
      // User already exists
      try {
        $stmt = "UPDATE users SET uid=:username, given_name=:given, family_name=:family, email=:email WHERE uuid=:sub";
        $sql = $db->prepare($stmt);
        $sql->bindParam(':sub', $oidc_user['sub']);
        $sql->bindParam(':username', $oidc_user['username']);
        $sql->bindParam(':given', $oidc_user['given_name']);
        $sql->bindParam(':family', $oidc_user['family_name']);
        $sql->bindParam(':email', $oidc_user['email']);
        $sql->execute();
      } catch (Jumbojett\PDOException $e) {
        echo("Error during existing user record update: " . $e->getMessage());
        die();
        $alert = array("danger", "Error during existing user record update: " . $e->getMessage());
      }
    }

    oidc_set_vars($oidc_user['sub'], $oidc_user['username'], $oidc_user['given_name'], $oidc_user['family_name'], $oidc_user['email']);
    unset($oidc_user);

    $_SESSION['is_signed_in'] = "true";

    header('Location: /');
?>
