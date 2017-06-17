<?php
  require_once('../data/settings.php');
  require_once('_init.php');
  require_once('../php/FileUtils.php');
  require_once('../php/MusicUtils.php');
  require_once('../php/UserUtils.php');

  // Interrupt if user not logged in
  if(empty($_SESSION['user_name'])) {
    header('Location: ../index.php');
  }

  // Get GET variables
  $song_id = $_GET['songid'];
  $song_info = FileUtils::getSongInfoFromID($song_id);

  if($song_info != false) {
    $file_path = $song_info['file_path'];
    $file_name = basename($file_path);
    $file_size = filesize($file_path);
    //header('Content-Type: audio/flac');
    header("X-Sendfile: " . $file_path);
    header("Accept-Ranges: bytes");
    header("Cache-Control: no-cache");
    header("Content-Type: application/octet-stream, audio/flac");
    header("Content-Transfer-Encoding: Binary");
    //if($file_size != 0)
    //  header("Content-Length: ". $file_size); // automatically set by XSendFile
    header("Keep-Alive: timeout=15, max=100");
    header("Connection: Keep-Alive");
    header("Content-Type: audio/flac");
    header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
    //header("Content-disposition: inline");
    readfile($file_path);
  }

?>
