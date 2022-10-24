<?php

namespace App\Patterns\FactoryPattern;

use App\Patterns\Mappers\CommentMap;
use App\Patterns\Mappers\PlayListMap;
use App\Patterns\Mappers\VideoListMap;
use App\Patterns\Mappers\VideoMap;
use App\Patterns\EnvApp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class YoutubeData implements IDataSource
{
    private $url = "https://youtube-v31.p.rapidapi.com/";

    private function getHttp(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withHeaders([
            'X-RapidAPI-Key' => env('APP_API_KEY'),
            'X-RapidAPI-Host' => 'youtube-v31.p.rapidapi.com'
        ]);
    }

    public function videos($key, $q)
    {
        $params = [
            "$key" => $q,
            "part" => "snippet,id",
            "regionCode" => "BO",
            "maxResults" => "50"
        ];

        $response = $this->getHttp()->get("{$this->url}search", $params);
        $listVideos = VideoListMap::mapData($response['items']);

        $this->insertVideosInCache("{$key}_{$q}", $listVideos);
        return $listVideos;
    }

    public function playListVideos($id)
    {
        $params = [
            "playlistId" => $id,
            "part" => "snippet",
            "maxResults" => "50"
        ];

        $response = $this->getHttp()->get("{$this->url}playlistItems", $params);
        $listVideos = PlayListMap::mapData($response['items']);
        $this->insertVideosInCache("playlist_$id", $listVideos);
        return $listVideos;
    }

    public function videoDetail($id)
    {
        $params = [
            "id" => $id,
            "part" => "snippet,statistics"
        ];

        $response = $this->getHttp()->get("{$this->url}videos", $params);
        $video = VideoMap::mapData($response['items']);
        $this->insertVideosInCache("video_$id", $video);
        return $video;
    }

    public function comments($videoId)
    {
        $params = [
            "videoId" => $videoId,
            "part" => "snippet",
            "type" => "video",
            "maxResults" => "100",
            "order" => "date"
        ];

        $response = $this->getHttp()->get("{$this->url}commentThreads", $params);
        $comments = CommentMap::mapData($response['items']);
        $this->insertVideosInCache("comments_$videoId", $comments);
        return $comments;

    }

    private function insertVideosInCache($keyCache, array $listVideos): void
    {
        Cache::forget($keyCache);
        Cache::put("$keyCache", $listVideos, EnvApp::$TIME_IN_CACHE);
    }
}
