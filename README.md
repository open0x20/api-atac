# api-ytv-converter
A RESTful API to download, convert and enhance media files.
### Dependencies
 - PHP 7.2 or higher
 - FFMPEG globally installed
 - wget, mv, rm and mkdir globally installed

### Endpoints
A better description can be found in the *swagger.json* file.

| Method | Path                  | Auth           | Description                                         |
|--------|-----------------------|----------------|-----------------------------------------------------|
| POST   | /add                  | basic          | Add a track and add to the conversion queue.        |
| POST   | /update               | basic          | Update a track and add to the conversion queue.     |
| POST   | /delete               | basic          | Delete a track and remove it from permanent storage.|
| GET    | /info/artists         | basic          | List of all unique artists.                         |
| GET    | /info/tracks          | basic          | List of all unique tracks (incl. metadata).         |
| GET    | /stream/{trackId}     | basic          | Returns the requested track for download.           |

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
