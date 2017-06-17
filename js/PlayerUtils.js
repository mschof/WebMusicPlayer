class PlayerUtils {

  static init()
  {

    // Events
    // Account controls
    var updatesongdb_button_element = document.getElementById("button-updatesongdb");
    updatesongdb_button_element.addEventListener("click", function(event) {
      RequestUtils.updateDbSongs();
    });

    var clearsongdb_button_element = document.getElementById("button-clearsongdb");
    clearsongdb_button_element.addEventListener("click", function(event) {
      RequestUtils.clearDbSongs();
    });

    // Search controls
    var search_input_element = document.getElementById("input-search");
    var search_button_element = document.getElementById("button-search");
    search_button_element.addEventListener("click", function(event) {
      RequestUtils.searchMusic(search_input_element.value);
    });

    search_input_element.addEventListener("keydown", function(event) {
      if(event.keyCode == 13) { // 13 = enter
        search_button_element.click();
      }
    });

    // Search/Playlist buttons
    var togglesearch_button_element = document.getElementById("button-togglesearch");
    togglesearch_button_element.addEventListener("click", function(event) {
      PlayerUtils.toggleSearchDisplay();
    });

    var toggleplaylist_button_element = document.getElementById("button-toggleplaylist");
    toggleplaylist_button_element.addEventListener("click", function(event) {
      PlayerUtils.togglePlaylistDisplay();
    });

    var closesearch_button_element = document.getElementById("button-closesearch");
    closesearch_button_element.addEventListener("click", function(event) {
      PlayerUtils.toggleSearchDisplay();
    });

    var closeplaylist_button_element = document.getElementById("button-closeplaylist");
    closeplaylist_button_element.addEventListener("click", function(event) {
      PlayerUtils.togglePlaylistDisplay();
    });

    // Player
    var player = document.getElementById("player");
    player.addEventListener("ended", function(event) {
      PlayerUtils.playerEnded();
    });

    // Set current song
    PlayerUtils.current_song_id = 0;
    PlayerUtils.current_song_title = "";
    PlayerUtils.current_song_artist = "";
    PlayerUtils.current_song_album = "";
    PlayerUtils.current_playlist_id = 0;
    PlayerUtils.stream_url = "../php/_stream.php?songid=";
  }

  static toggleSearchDisplay()
  {
    var element = document.getElementById("search-container");
    element.classList.toggle("hidden");
  }

  static togglePlaylistDisplay()
  {
    var element = document.getElementById("playlist-container");
    element.classList.toggle("hidden");
  }

  static updateSearchResultListeners()
  {
    // Add listener for play button
    var button_play_collection = document.getElementsByClassName("button-play");
    for(var i = 0; i < button_play_collection.length; i++) {
      button_play_collection[i].addEventListener("click", function(event) {
        // Set new source and start playing
        var parent_row = this.parentElement.parentElement;
        PlayerUtils.playerPlaySong(parseInt(parent_row.getAttribute("data-songid")), -1);
      });
    }

    // Add listener for playlist button
    var button_toplaylist_collection = document.getElementsByClassName("button-toplaylist");
    for(var i = 0; i < button_toplaylist_collection.length; i++) {
      button_toplaylist_collection[i].addEventListener("click", function(event) {
        // Get data and add to playlist
        var parent_row = this.parentElement.parentElement;
        var song_title = parent_row.getAttribute("data-songtitle");
        var song_id = parent_row.getAttribute("data-songid");
        PlayerUtils.songAddToPlaylist(song_title, song_id);
      });
    }
  }

  static songAddToPlaylist(song_title, song_id)
  {
    // Add element
    var table_playlist_body = document.getElementById("table-playlist-body");
    var table_row = document.createElement("tr");
    table_row.dataset.songid = song_id;
    table_row.dataset.songtitle = song_title;
    table_row.classList.add("playlist-row");
    var row_title = document.createElement("td");
    var row_actions = document.createElement("td");
    var button_play = document.createElement("button");
    button_play.type = "button";
    button_play.classList.add("button-play");
    button_play.classList.add("button");
    button_play.innerHTML = "Play";
    var button_remove = document.createElement("button");
    button_remove.type = "button";
    button_remove.classList.add("button");
    button_remove.innerHTML = "Remove";
    var button_up = document.createElement("button");
    button_up.type = "button";
    button_up.classList.add("button");
    button_up.innerHTML = "Up";
    var button_down = document.createElement("button");
    button_down.type = "button";
    button_down.classList.add("button");
    button_down.innerHTML = "Down";
    row_title.innerHTML = song_title;
    row_actions.appendChild(button_play);
    row_actions.appendChild(button_remove);
    row_actions.appendChild(button_up);
    row_actions.appendChild(button_down);
    table_row.appendChild(row_title);
    table_row.appendChild(row_actions);
    table_playlist_body.appendChild(table_row);

    // Add listener to play button
    button_play.addEventListener("click", function(event) {
      // Set new source and start playing
      var parent_row = this.parentElement.parentElement;
      var playlist_id = parseInt(UIUtils.getIndexInParent(parent_row));
      PlayerUtils.playerPlaySong(parseInt(parent_row.getAttribute("data-songid")), playlist_id);
    });

    // Add listener to remove button
    button_remove.addEventListener("click", function(event) {
      PlayerUtils.songRemoveFromPlaylist(this);
    });

    // Add listener to up button
    button_up.addEventListener("click", function(event) {
      PlayerUtils.songPlaylistMoveUp(this);
    });

    // Add listener to down button
    button_down.addEventListener("click", function(event) {
      PlayerUtils.songPlaylistMoveDown(this);
    });
  }

  static songRemoveFromPlaylist(button_element)
  {
    var table_playlist_body = document.getElementById("table-playlist-body");
    var row = button_element.parentElement.parentElement;
    table_playlist_body.removeChild(row);
  }

  static songPlaylistMoveUp(button_element)
  {
    var row = button_element.parentElement.parentElement;
    if(row.previousElementSibling)
      row.parentNode.insertBefore(row, row.previousElementSibling);
  }

  static songPlaylistMoveDown(button_element)
  {
    var row = button_element.parentElement.parentElement;
    if(row.nextElementSibling)
      row.parentNode.insertBefore(row.nextElementSibling, row);
  }
  
  static updateAlbumCover(song_title, song_album)
  {
    var image_cover_element = document.getElementById("image-cover");
    image_cover_element.classList.toggle("image-loading");
    var search_string = song_title.split(" ").join("+") + "+" + song_album.split(" ").join("+");
    RequestUtils.requestAlbumCover(image_cover_element, search_string);
  }

  static playerPlaySong(song_id, playlist_id)
  {
    var player = document.getElementById("player");
    player.src = PlayerUtils.stream_url + song_id;
    player.play();

    PlayerUtils.current_song_id = song_id;
    PlayerUtils.current_playlist_id = playlist_id;

    // Start update
    RequestUtils.requestSongInfo(song_id, PlayerUtils.updateCurrentSongInfo);
  }

  static updateCurrentSongInfo(song_info)
  {
    var song_title = song_info['title'];
    var song_album = song_info['album'];
    var song_artist = song_info['artist'];
    var song_audioinfo = (parseInt(song_info['sample_rate']) / 1000) + "kHz/" + song_info['bits_per_sample'] + "bit (" + song_info['dataformat'].toUpperCase() + ")";

    PlayerUtils.current_song_title = song_title;
    PlayerUtils.current_song_artist = song_artist;
    PlayerUtils.current_song_album = song_album;

    // Update elements
    document.getElementById("songinfo-title").innerHTML = song_title;
    document.getElementById("songinfo-album").innerHTML = song_album;
    document.getElementById("songinfo-artist").innerHTML = song_artist;
    document.getElementById("songinfo-audioinfo").innerHTML = song_audioinfo;

    // Start update of cover
    PlayerUtils.updateAlbumCover(song_title, song_album);
  }

  static playerEnded()
  {
    if(PlayerUtils.current_playlist_id >= 0)
      PlayerUtils.playerPlayNext();
  }

  static playerPlayNext()
  {
    var table_playlist_body = document.getElementById("table-playlist");
    for(var i = 0; i < table_playlist_body.rows.length; i++) {
      var current_row = table_playlist_body.rows[i];
      if(parseInt(current_row.getAttribute("data-songid")) == PlayerUtils.current_song_id && current_row.nextSibling != null) {
        var next_row = current_row.nextSibling;
        var playlist_id = parseInt(UIUtils.getIndexInParent(next_row));
        PlayerUtils.playerPlaySong(parseInt(next_row.getAttribute("data-songid")), playlist_id);
        break;
      }
    }
  }

}

document.onreadystatechange = function() {
  if(document.readyState === 'complete') {
    PlayerUtils.init();
  }
}