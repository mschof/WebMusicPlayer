<?php
  require_once('../data/settings.php');
  require_once('_init.php');
  require_once('UserUtils.php');

  // Destroy session
  session_destroy();

  // Redirect to index
  header('Location: ../index.php');

?>
