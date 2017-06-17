<?php
  // Error reporting (DISABLE LATER!)
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  // Paths
  $ROOT_PATH = realpath(dirname(__FILE__)) . '/..';
  $DB_PATH = 'data/db.sqlite';
  $DB_FULL_PATH = $ROOT_PATH . '/' . $DB_PATH;
  $MUSIC_PATH = '/var/www/html/media/Music'; // full absolute path to music

  // Website settings
  $MESSAGE_LIFETIME = 3; // in seconds
?>