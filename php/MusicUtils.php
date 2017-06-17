<?php

class MusicUtils
{
  private static function init()
  {
    // ...
  }

  public static function getMainTags($file_path)
  {
    $getID3 = new getID3;
    $file_info = $getID3->analyze($file_path);
    $main_tags = array();
    $main_tags['title'] = reset($file_info['tags'])['title'][0];
    $main_tags['artist'] = reset($file_info['tags'])['artist'][0];
    $main_tags['album'] = reset($file_info['tags'])['album'][0];
    return $main_tags;
  }

  public static function getSongTitle($file_path)
  {
    $getID3 = new getID3;
    $file_info = $getID3->analyze($file_path);
    $title = reset($file_info['tags'])['title'][0];
    return $title;
  }

  public static function getSongArtist($file_path)
  {
    $getID3 = new getID3;
    $file_info = $getID3->analyze($file_path);
    $artist = reset($file_info['tags'])['artist'][0];
    return $artist;
  }

  public static function getSongAlbum($file_path)
  {
    $getID3 = new getID3;
    $file_info = $getID3->analyze($file_path);
    $album = reset($file_info['tags'])['album'][0];
    return $album;
  }

  public static function getSongAudioInfo($file_path)
  {
    $getID3 = new getID3;
    $audio_info = array();
    $file_info = $getID3->analyze($file_path);
    $audio_info['dataformat'] = $file_info['audio']['dataformat'];
    $audio_info['sample_rate'] = $file_info['audio']['sample_rate'];
    $audio_info['bits_per_sample'] = $file_info['audio']['bits_per_sample'];
    return $audio_info;
  }

}

?>
