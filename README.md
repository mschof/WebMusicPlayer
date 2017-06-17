# WebMusicPlayer
This is a minimalist web application to stream music from your own server, written purely in JS and PHP.

## File formats
Currently, only FLAC files are supported (lossless is the way to go). This means that this web application can only by used with Mozilla Firefox, because it's the only one natively supporting FLAC files in HTML5 audio elements. I hope that more browsers will be able to play FLAC files in the future.

## Planned features
Among the planned features is a more sophisticated playlist, possibly even multiply playlists, which can be stored in the database. I also want to add a user control panel to create additional users with limited rights (e.g. only streaming rights). The stylesheet is also something I would like to improve.

## XSendFile
This web application uses XSendFile in order to play music files before the full download has finished. It also makes it possible to seek using the HTML5 audio player.
In order to activate XSendFile you first need to install mod_xsendfile on your machine and enable it in your web server configuration. Then you need to allow XSendFile to access the files in your music folder. On Apache, this can be done by adding following line to your httpd.conf:

`XSendFilePath <path_to_your_music>`
