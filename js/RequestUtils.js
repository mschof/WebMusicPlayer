class RequestUtils {

  static searchMusic(search_string)
  {
    if(search_string.length >= 3) {
      var xmlhttp = new XMLHttpRequest();
      var params = "request=songSearch&searchstring=" + search_string;
      xmlhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
          //alert(this.responseText);
          var table_results = document.getElementById("table-search-results");
          var music_files = JSON.parse(this.responseText);

          // Add table head
          //table_results.innerHTML = '<thead><tr><th>Title</th><th>Artist</th><th>Album</th><th>Actions</th></tr></thead>';
          table_results.innerHTML = '<thead><tr><th>Title</th><th>Artist</th><th>Actions</th></tr></thead>';

          // Create table body
          var table_body = document.createElement("tbody");

          // Add data
          for(var i = 0; i < music_files.length; i++) {
            var table_row = document.createElement("tr");
            table_row.classList.add("search-results-row");
            table_row.dataset.songid = music_files[i].ID;
            table_row.dataset.songtitle = music_files[i].title;
            var row_title = document.createElement("td");
            var row_artist = document.createElement("td");
            //var row_album = document.createElement("td");
            var row_actions = document.createElement("td");
            var button_play = document.createElement("button");
            button_play.type = "button";
            button_play.classList.add("button-play");
            button_play.classList.add("button");
            button_play.innerHTML = "Play";
            var button_toplaylist = document.createElement("button");
            button_toplaylist.type = "button";
            button_toplaylist.classList.add("button-toplaylist");
            button_toplaylist.classList.add("button");
            button_toplaylist.innerHTML = "To playlist";
            row_title.innerHTML = music_files[i].title;
            row_artist.innerHTML = music_files[i].artist;
            //row_album.innerHTML = music_files[i].album;
            row_actions.appendChild(button_play);
            row_actions.appendChild(button_toplaylist);
            table_row.appendChild(row_title);
            table_row.appendChild(row_artist);
            //table_row.appendChild(row_album);
            table_row.appendChild(row_actions);
            table_body.appendChild(table_row);
          }

          // Add table body
          table_results.appendChild(table_body);
          
          // Update search result number
          document.getElementById("search-results-count").innerHTML = music_files.length;

          // Update event listeners for rows
          PlayerUtils.updateSearchResultListeners();
        }
      };
      xmlhttp.open("POST", "../php/_request_functions.php", true);
      xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xmlhttp.send(params);
    }
  }

  static updateDbSongs()
  {
    var xmlhttp = new XMLHttpRequest();
    var params = "request=updateDb";
    xmlhttp.onreadystatechange = function() {
      if(this.readyState == 4 && this.status == 200) {
        alert("Updated database!");
        //alert(this.responseText);
      }
    };
    xmlhttp.open("POST", "../php/_request_functions.php", true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.send(params);
  }

  static clearDbSongs()
  {
    var xmlhttp = new XMLHttpRequest();
    var params = "request=clearDb";
    xmlhttp.onreadystatechange = function() {
      if(this.readyState == 4 && this.status == 200) {
        alert("Cleared database!");
        //alert(this.responseText);
      }
    };
    xmlhttp.open("POST", "../php/_request_functions.php", true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.send(params);
  }

  static getSongInfo(song_id)
  {
    var request = new XMLHttpRequest();
    var params = "request=songInfo&songid=" + song_id;
    xmlhttp.open("POST", "../php/_request_functions.php", true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(params);

    if (request.status === 200) {
      var data = JSON.parse(request.responseText);
      return data;
    }
  }

  static requestSongInfo(song_id, updateCallBack)
  {
    var xmlhttp = new XMLHttpRequest();
    var params = "request=songInfo&songid=" + song_id;
    xmlhttp.onreadystatechange = function() {
      if(this.readyState == 4 && this.status == 200) {
        //alert(this.responseText);
        var song_info = JSON.parse(this.responseText);
        updateCallBack(song_info);
      }
    };
    xmlhttp.open("POST", "../php/_request_functions.php", true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.send(params);
  }

  static requestAlbumCover(image_cover_element, search_string)
  {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if(this.readyState == 4 && this.status == 200) {
        var imageURL;
        var data = JSON.parse(this.responseText);
        if(data.resultCount > 0) {
          imageURL = data.results[0].artworkUrl100.replace("100x100", "400x400");
        }
        else {
          imageURL = "../images/nocover.png";
        }
        // Change only if the song is still the most current one
        //if(current_song_id == song_id) {
          image_cover_element.classList.toggle("image-loading");
          image_cover_element.src = imageURL;
        //}
      }
    };
    xmlhttp.open("GET", "https://itunes.apple.com/search?term=" + search_string, true);
    xmlhttp.send();
  }

}
