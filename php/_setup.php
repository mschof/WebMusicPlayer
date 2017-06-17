<?php
  require_once('../data/settings.php');
  require_once('_init.php');
  require_once('UserUtils.php');

  // Interrupt if an admin account already exists or if user is logged in (user_logged_in -> admin_acc_exists, hopefully...)
  if(UserUtils::adminCount() > 0 || !empty($_SESSION['user_name'])) {
    header('Location: ../index.php?newuser=0');
  }

  // Get POST variables
  $user_name = UserUtils::userNameSanitize($_POST["input-name"]);
  $user_password = $_POST["input-password"];

  // Check if this user already exists
  $user_exists = UserUtils::userExists($user_name);
  if($user_exists == false) {
    // Add new admin user
    UserUtils::userAdd($user_name, $user_password, 0);

    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_role'] = 0;

    // Redirect to player
    header('Location: ../pages/player.php?firststart=1');
  }
  else {
    // User already exists, back to index
    header('Location: ../index.php?newuser=0');
  }

?>
