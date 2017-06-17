<?php

class FileUtils
{
  private static function init()
  {
    // ...
  }

  public static function fileNameSanitize($file_name)
  {
    // Maybe this changes behavior in the future...
    $name_filtered = htmlspecialchars($file_name, ENT_QUOTES, 'UTF-8');
    return $name_filtered;
  }

  public static function getSongsByAnything($search_string)
  {
    $music_files = array();

    // Open database
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;

    // Statement
    $query = "SELECT ID, title, artist, album FROM Song WHERE (title LIKE :search_string) OR (album LIKE :search_string) OR (artist LIKE :search_string) ORDER BY LOWER(title)";
    $stmt = $db->prepare($query);
    if($stmt) {
      $stmt->bindValue(':search_string', '%' . $search_string . '%', SQLITE3_TEXT);
      $result = $stmt->execute();

      // Count
      while($entry = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($music_files, $entry);
      }

    }
    else {
      return false;
    }
    
    return $music_files;
  }

  public static function echoMusicFiles($extension)
  {
    global $MUSIC_PATH;
    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($MUSIC_PATH));
    foreach($objects as $name => $object) {
      if($objects->isDir() || $objects->getExtension() != $extension) continue;
      echo $object->getFilename();
    }
  }

  public static function countMusicFiles($extension)
  {
    global $MUSIC_PATH;
    $count = 0;
    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($MUSIC_PATH));
    foreach($objects as $name => $object) {
      if($objects->isDir() || $objects->getExtension() != $extension) continue;
      $count++;
    }
    return $count;
  }

  public static function insertSongsIntoDb($music_files)
  {
    // Open database
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;

    $db->exec('BEGIN;');

    // Statement
    foreach($music_files as $file_path) {
      $main_tags = MusicUtils::getMainTags($file_path);
      $query = "INSERT INTO Song (title, artist, album, file_path) VALUES (:title, :artist, :album, :file_path)";
      $stmt = $db->prepare($query);
      if($stmt) {
        $stmt->bindValue(':title', $main_tags['title'], SQLITE3_TEXT);
        $stmt->bindValue(':artist', $main_tags['artist'], SQLITE3_TEXT);
        $stmt->bindValue(':album', $main_tags['album'], SQLITE3_TEXT);
        $stmt->bindValue(':file_path', $file_path, SQLITE3_TEXT);
        $stmt->execute();
      }
      else {
        $db->exec('COMMIT;');
        return false;
      }
    }

    $db->exec('COMMIT;');

    return true;
  }

  public static function isSongInDb($file_path)
  {
    // TODO: if an error occurs the output becomes interpreted as "song does not exist", fix this!
    $song_exists = false;

    // Check database
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;

    // Statement
    $query = "SELECT ID FROM Song WHERE file_path=:file_path LIMIT 1";
    $stmt = $db->prepare($query);
    if($stmt) {
      $stmt->bindValue(':file_path', $file_path, SQLITE3_TEXT);
      $result = $stmt->execute();

      // Count
      while($entry = $result->fetchArray(SQLITE3_ASSOC)) {
        $song_exists = true;
        break;
      }
    }
    else {
      return false;
    }

    return $song_exists;
  }

  public static function updateDbSongs()
  {
    global $MUSIC_PATH;
    $music_files = array();
    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($MUSIC_PATH));
    foreach($objects as $name => $object) {
      if($objects->isDir()) continue;
      $file_path = $objects->getPathname();
      if(self::isSongInDb($file_path) == false) {
        array_push($music_files, $file_path);
      }
    }

    // Insert songs into db
    $result = self::insertSongsIntoDb($music_files);
    return $result;
  }

  public static function clearDbSongs()
  {
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;

    $db->exec('DELETE FROM Song;');
  }

  public static function getSongInfoFromID($song_id)
  {
    // Check database
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;

    // Statement
    $query = "SELECT title, album, artist, file_path FROM Song WHERE ID=:ID LIMIT 1";
    $stmt = $db->prepare($query);
    if($stmt) {
      $stmt->bindValue(':ID', $song_id, SQLITE3_INTEGER);
      $result = $stmt->execute();

      // Get file_path
      while($entry = $result->fetchArray(SQLITE3_ASSOC)) {
        return $entry;
      }
    }
    else {
      return false;
    }

    return false;
  }

}

?>
