# api-ytv-converter
A RESTful API to download, convert and enhance media files.
### Dependencies
 - PHP 7.2 or higher
 - FFMPEG globally installed
 - wget, ps, grep, mv, rm and mkdir globally installed

### Endpoints
A better description can be found in the *swagger.json* file.

| Method | Path                  | Auth           | Description                                                                                                 |
|--------|-----------------------|----------------|-------------------------------------------------------------------------------------------------------------|
| POST   | /add                  | basic          | Add a track and add to the conversion queue.                                                                |
| POST   | /update               | basic          | Update a track and add to the conversion queue.                                                             |
| POST   | /delete               | basic          | Delete a track and remove it from permanent storage.                                                        |
| GET    | /stream/{trackId}     | basic          | Returns the requested track for download.                                                                   |
| GET    | /info/artists         | basic          | List of all unique artists.                                                                                 |
| GET    | /info/tracks          | basic          | List of all unique tracks (incl. metadata).                                                                 |
| GET    | /info/check_ytv       | basic          | Validates given ytv url and extracts metadata.                                                              |
| GET    | /info/check_cover     | basic          | Validates given cover url and extracts metadata.                                                            |
| GET    | /info/stats           | basic          | Outputs some application metadata and statistics.                                                           |
| POST   | /info/difference      | basic          | Calculates the difference between the provided list of filenames and all files present on the data directory|

### Example Requests
#### POST /add
```
Request:
{
  "urlYtv": "https://www.youtube.com/watch?v=af59U2BRRAU",
  "artists": ["Rammstein"],
  "featuring": [],
  "title": "Rosenrot",
  "album": "Rosenrot",
  "urlCover": "https://live.staticflickr.com/33/64120185_9c754331e3.jpg"
}

Response:
{
  "meta": {
    "code": 200,
    "errors": []
  },
  "data": {
    "id": 152
  }
}
```
#### POST /update
```
Request:
{
  "trackId": 152,
  "urlYtv": "https://www.youtube.com/watch?v=af59U2BRRAU",
  "artists": ["Rammstein"],
  "featuring": [],
  "title": "Rosenrot",
  "album": "Rosenrot",
  "urlCover": "https://live.staticflickr.com/33/64120185_9c754331e3.jpg"
}

Response:
{
  "meta": {
    "code": 200,
    "errors": []
  },
  "data": {
    "id": 152
  }
}
```
#### GET /info/tracks
```
{
  "meta": {
    "code": 200,
    "errors": []
  },
  "data": {
    "meta": {
      "countOverall": 1,
      "countRequest": 1
    },
    "tracks": [
      {
        "trackId": 152,
        "urlYtv": "https://www.youtube.com/watch?v=af59U2BRRAU",
        "artists": ["Rammstein"],
        "featuring": [],
        "title": "Rosenrot",
        "album": "Rosenrot",
        "urlCover": "https://live.staticflickr.com/33/64120185_9c754331e3.jpg"
      }
    ]
  }
}
```
#### POST /info/difference
```
Request:
{
    "filenames": [
      "1edb0a07d7f5676736541f02179dcb77.mp3",
      "0d37a4e4aa96dce2c002e4990e01717b.mp3"
    ]
}

Response:
{
  "meta": {
    "code": 200,
    "errors": []
  },
  "data": {
    "differenceCount": 6,
    "difference": [
      "08ad7d280d052884936553d2dc836066.mp3",
      "0ac14b6ae081cf6294fd6c8e37cbc9ce.mp3",
      "0af2ac69397f6f9edf3fda2f629a22c6.mp3",
      "81fddc6fac97683a8fe677fdd7b6b204.mp3",
      "af87bf7e83a1d0a601d6a08a20646f6e.mp3",
      "ef01fe3d7241d703143052f076d775fa.mp3"
    ]
  }
}
```
