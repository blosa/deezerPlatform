# deezerPlatform
Platform for projects with Deezer API

## Use API Rest
## User
### Read all users
GET URL/user/read.php
### Read one user
GET URL/user/read_one.php?user_id=$user_id
### Read favorite songs of an user
GET URL/user/read_fav_song.php?user_id=$user_id&song_id=$song_id
### Add one song to favorite
POST URL/user/add_fav_song.php?user_id=$user_id&song_id=$song_id
### Delete one song to favorite
DELETE URL/user/delete_fav_song.php?user_id=$user_id&song_id=$song_id
## Song
### Read all songs
GET URL/song/read.php
### Read one song
GET URL/song/read_one.php?song_id=$song_id