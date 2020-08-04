<?php
    $PAGE_NAME = "Logging in...";
    require_once __DIR__ . "/../includes/prereqs.php";

    // Perform the OIDC authentication
    try {
      $oidc->authenticate();
      $oidc_user = array(
        'sub' => $oidc->requestUserInfo('sub'),
        'username' => $oidc->requestUserInfo('preferred_username'),
        'given_name' => $oidc->requestUserInfo('given_name'),
        'family_name' => $oidc->requestUserInfo('family_name'),
        'email' => $oidc->requestUserInfo('email'),
      );
    } catch (Jumbojett\OpenIDConnectClientException $e) {
      echo("Error during OpenID Connect authentication: " . $e->getMessage() . "<br>");
    }

    // Check if the user already exists
    try {
      $user_exist_sql = $db->prepare("SELECT uuid FROM users WHERE uuid=:uuid");
      $user_exist_sql->bindParam(':uuid', $oidc_user['sub']);
      $user_exist_sql->execute();
      $result = $user_exist_sql->setFetchMode(PDO::FETCH_ASSOC); // If user doesn't exist, $result will be null
    } catch (PDOException $e) {
      echo("Error: " . $e->getMessage() . "<br>");
    }

    if ($result != null) {
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
      } catch (PDOException $e) {
        echo("Error running SQL (Update existing user): <br>" . $e->getMessage() . "<br>");
      }
    } else {
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
      } catch (PDOException $e) {
        echo("Error running SQL (Add new user): <br>" . $e->getMessage() . "<br>");
      }
    }

    oidc_set_vars($oidc_user['sub'], $oidc_user['username'], $oidc_user['given_name'], $oidc_user['family_name'], $oidc_user['email']);
    unset($oidc_user);

    $_SESSION['is_signed_in'] = "true";

    header('Location: /');
?>
