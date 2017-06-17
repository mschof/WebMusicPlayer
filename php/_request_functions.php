<?php
  require_once('getid3/getid3.php');
  require_once('../data/settings.php');
  require_once('_init.php');
  require_once('../php/FileUtils.php');
  require_once('../php/MusicUtils.php');
  require_once('../php/UserUtils.php');

  function requestUpdateDb()
  {
    FileUtils::updateDbSongs();
  }

  function requestClearDb()
  {
    FileUtils::clearDbSongs();
  }

  function requestSongSearch($search_string)
  {
    $final_search_string = filter_var($search_string, FILTER_SANITIZE_STRING);
    $music_files = FileUtils::getSongsByAnything($final_search_string);
    echo json_encode($music_files);
  }

  function requestSongInfo($song_id)
  {
    $final_song_id = filter_var($song_id, FILTER_SANITIZE_NUMBER_INT);
    $song_info = FileUtils::getSongInfoFromID($final_song_id);
    $song_file_path = $song_info['file_path'];
    $song_audioinfo = MusicUtils::getSongAudioInfo($song_file_path);
    $song_info = array_merge($song_info, $song_audioinfo);
    echo json_encode($song_info);
  }

  // Interrupt if user not logged in
  if(empty($_SESSION['user_name'])) {
    header('Location: ../index.php');
  }

  if(isset($_POST['request']) && !empty($_POST['request'])) {
    $request = $_POST['request'];
    switch($request) {
      // Request to update DB
      case 'updateDb':
        // Check for admin
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] != 0) {
          header('Location: ../index.php');
          break;
        }
        requestUpdateDb();
        break;
      // Request to clear DB
      case 'clearDb':
        // Check for admin
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] != 0) {
          header('Location: ../index.php');
          break;
        }
        requestClearDb();
        break;
      // Search song
      case 'songSearch':
        requestSongSearch($_POST['searchstring']);
        break;
      // Request song info
      case 'songInfo':
        requestSongInfo($_POST['songid']);
        break;
    }
  }

?>
