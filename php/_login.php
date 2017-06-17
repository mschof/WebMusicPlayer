<?php
  require_once('../data/settings.php');
  require_once('_init.php');
  require_once('UserUtils.php');

  // Interrupt if user already logged in
  if(!empty($_SESSION['user_name'])) {
    header('Location: ../index.php');
  }

  // Get POST variables
  $user_name = UserUtils::userNameSanitize($_POST["input-name"]);
  $user_password = $_POST["input-password"];

  // Check login credentials
  $login_check = UserUtils::checkLogin($user_name, $user_password);
  if($login_check == true) {
    // Log in user
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_role'] = UserUtils::getUserRole($user_name);

    // Redirect to player
    header('Location: ../pages/player.php');
  }
  else {
    // Login failed, back to index
    header('Location: ../index.php?login=0');
  }

?>
