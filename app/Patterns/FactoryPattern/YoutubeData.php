<?php

namespace App\Patterns\FactoryPattern;

use App\Patterns\Decorators\PlayListMap;
use App\Patterns\Decorators\VideoListMap;
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
        $this->insertVideosInCache($id, $listVideos);
        return $listVideos;
    }

    public function videoDetail($id)
    {
    }

    public function comments($videoId)
    {
    }

    public function suggestedVideos($videoId)
    {
    }

    private function insertVideosInCache($keyCache, array $listVideos): void
    {
        Cache::forget($keyCache);
        Cache::put("$keyCache", $listVideos, EnvApp::$TIME_IN_CACHE);
    }
}
