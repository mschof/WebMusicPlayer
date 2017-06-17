<?php
  // External libs
  require_once('../php/getid3/getid3.php');

  require_once('../data/en.php');
  require_once('../data/settings.php');
  require_once("../php/_init.php");
  require_once('../php/FileUtils.php');
  require_once('../php/MusicUtils.php');
  require_once('../php/UserUtils.php');

  // Check if logged in, redirect to pages/player.php if no
  if(empty($_SESSION['user_name'])) {
    header('Location: ../index.php');
  }

?>

<html>
  <head>
    <meta charset="utf-8">
    <title>WebPlayer</title>
    <meta name="description" content="WebPlayer for FLAC files using PHP and JS">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/UIUtils.js"></script>
    <script src="../js/PlayerUtils.js"></script>
    <script src="../js/RequestUtils.js"></script>
  </head>
  <body>
    <div id="account-controls">
      <span class="account-controls-item account-controls-item-left">User: <?php echo $_SESSION['user_name']; ?> </span>
      <span class="account-controls-item account-controls-item-left">Music files: <?php echo FileUtils::countMusicFiles("flac"); ?> </span>
      <button id="button-togglesearch" class="button account-controls-item account-controls-item-left" type="button"><?php echo (string)$GLOBALS['LANG_SEARCH_TITLE']; ?></button>
      <button id="button-toggleplaylist" class="button account-controls-item account-controls-item-left" type="button"><?php echo (string)$GLOBALS['LANG_PLAYLIST_TITLE']; ?></button>
      <form class="account-controls-item account-controls-item-right" action="../php/_logout.php">
        <input class="button account-controls-item" type="submit" value="<?php echo (string)$GLOBALS['LANG_BUTTON_LOGOUT']; ?>" >
      </form>
      <input id="button-clearsongdb" class="button account-controls-item account-controls-item-right" type="button" value="<?php echo (string)$GLOBALS['LANG_BUTTON_CLEARSONGDB']; ?>" >
      <input id="button-updatesongdb" class="button account-controls-item account-controls-item-right" type="button" value="<?php echo (string)$GLOBALS['LANG_BUTTON_UPDATESONGDB']; ?>" >
    </div>

    <div id="content-area">

      <div id="songinfo-container">
        <img id="image-cover" class="songinfo-container-item" src="../images/nocover.png" alt="cover">
        <span id="songinfo-title" class="songinfo-container-item">-<?php echo (string)$GLOBALS['LANG_SONGINFO_TITLE']; ?>-</span>
        <span id="songinfo-album" class="songinfo-container-item">-<?php echo (string)$GLOBALS['LANG_SONGINFO_ALBUM']; ?>-</span>
        <span id="songinfo-artist" class="songinfo-container-item">-<?php echo (string)$GLOBALS['LANG_SONGINFO_ARTIST']; ?>-</span>
        <span id="songinfo-audioinfo" class="songinfo-container-item">-<?php echo (string)$GLOBALS['LANG_SONGINFO_AUDIOINFO']; ?>-</span>
      </div>

      <div id="search-container">
        <button id="button-closesearch" class="button" type="button">[x]</button>
        <span id="search-title"><?php echo (string)$GLOBALS['LANG_SEARCH_TITLE']; ?></span>

        <div id="search-controls">
          <input id="input-search" type="text" name="input-search" placeholder="<?php echo (string)$GLOBALS['LANG_SEARCH']; ?>" >
          <input id="button-search" class="button" type="button" value="<?php echo (string)$GLOBALS['LANG_BUTTON_SEARCH']; ?>" >
        </div>

        <table id="table-search-results">

          <!-- Populated by PHP -->
        </table>
        <span id="search-results-count"></span>
        
      </div>

      <div id="playlist-container">
        <button id="button-closeplaylist" class="button" type="button">[x]</button>
        <span id="playlist-title"><?php echo (string)$GLOBALS['LANG_PLAYLIST_TITLE']; ?></span>

        <table id="table-playlist">
          <thead><tr><th>Title</th><th>Actions</th></tr></thead>
          <tbody id="table-playlist-body">
            <!-- Populated by JavaScript -->
          </tbody>
        </table>
      </div>

    </div>

    <div id="player-container">
      <audio id="player" controls>
        Your browser does not support the audio element.
      </audio>
    </div>

  </body>
</html>