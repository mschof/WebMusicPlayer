<?php
  require_once('data/en.php');
  require_once('data/settings.php');
  require_once("php/_init.php");
  require_once('php/UserUtils.php');

  // Check if logged in, redirect to pages/player.php if yes
  if(!empty($_SESSION['user_name'])) {
    header('Location: pages/player.php');
  }

  // Check if a admin exists (if no -> fresh setup)
  $admin_count = UserUtils::adminCount();
?>

<html>
  <head>
    <meta charset="utf-8">
    <title>WebMusicPlayer</title>
    <meta name="description" content="Web player for FLAC files using PHP and JS">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/UIUtils.js"></script>
  </head>

  <?php
  // Check if something failed
  if(isset($_GET['login']) && $_GET['login'] == 0) {
    echo '<script>UIUtils.showTopMessage("' . (string)$GLOBALS['LANG_LOGIN_FAILED'] . '", ' . (string)$GLOBALS['MESSAGE_LIFETIME'] . ');</script>';
  }
  ?>

  <body>
    <?php if($admin_count > 0) : ?>
      <!-- Display login form -->
      <div id="login-container">
        <span id="login-title"><?php echo $GLOBALS['LANG_LOGIN_TITLE']; ?></span>
        <form id="login-form" action="php/_login.php" method="post">
          <input type="text" name="input-name" placeholder="<?php echo (string)$GLOBALS['LANG_USER_NAME']; ?>" >
          <input type="password" name="input-password" placeholder="<?php echo (string)$GLOBALS['LANG_USER_PASSWORD']; ?>" >
          <input class="button" type="submit" value="<?php echo (string)$GLOBALS['LANG_BUTTON_SUBMIT']; ?>" >
        </form>
      </div>
    <?php else : ?>
      <!-- Display fresh setup form -->
      <div id="setup-container">
        <span id="setup-title"><?php echo $GLOBALS['LANG_SETUP_TITLE']; ?></span>
        <form id="setup-form" action="php/_setup.php" method="post">
          <input type="text" name="input-name" placeholder="<?php echo (string)$GLOBALS['LANG_USER_NAME']; ?>" >
          <input type="password" name="input-password" placeholder="<?php echo (string)$GLOBALS['LANG_USER_PASSWORD']; ?>" >
          <input class="button" type="submit" value="<?php echo (string)$GLOBALS['LANG_BUTTON_SUBMIT']; ?>" >
        </form>
      </div>
    <?php endif; ?>
  </body>
</html>