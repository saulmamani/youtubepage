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
    private string $url = "https://youtube-v31.p.rapidapi.com/";

    private function getHttp(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withHeaders([
            'X-RapidAPI-Key' => env('APP_API_KEY', 'ea3613d5b3msh663ec4def8dea8fp1d9c00jsn0c281b455593'),
            'X-RapidAPI-Host' => 'youtube-v31.p.rapidapi.com'
        ]);
    }

    public function videos($key, $q)
    {
        $params = [
            "$key" => $q,
            "part" => "snippet,id",
            "regionCode" => "BO",
            "maxResults" => "100"
        ];

        $response = $this->getHttp()->get("{$this->url}search", $params);
        $listVideos = VideoListMap::mapData($response['items']);

        $this->insertDataInCache("{$key}_{$q}", $listVideos);
        return $listVideos;
    }

    public function playListVideos($kind, $id)
    {
        $params = [
            "playlistId" => $id,
            "part" => "snippet",
            "maxResults" => "100"
        ];

        $response = $this->getHttp()->get("{$this->url}playlistItems", $params);
        $listVideos = PlayListMap::mapData($response['items']);
        $this->insertDataInCache("{$kind}_{$id}", $listVideos);
        return $listVideos;
    }

    public function videoDetail($kind, $id)
    {
        $params = [
            "id" => $id,
            "part" => "snippet,statistics"
        ];

        $response = $this->getHttp()->get("{$this->url}videos", $params);
        if (isset($response['items'])) {
            $video = VideoMap::mapData($response['items']);
            $this->insertDataInCache("{$kind}_{$id}", $video);
            return $video;
        }

        $this->throwExeption($response['error']);
    }

    public function comments($kind, $videoId)
    {
        $params = [
            "videoId" => $videoId,
            "part" => "snippet",
            "type" => "video",
            "maxResults" => "100",
            "order" => "date"
        ];

        $response = $this->getHttp()->get("{$this->url}commentThreads", $params);
        if (isset($response['items'])) {
            error_log("no deberia entrar");
            $comments = CommentMap::mapData($response['items']);
            $this->insertDataInCache("{$kind}_{$videoId}", $comments);
            return $comments;
        }

        $this->throwExeption($response['error']);
    }

    /**
     * @throws \Exception
     */
    private function throwExeption($error1)
    {
        $error = $error1;
        throw new \Exception($error['message'], $error['code']);
    }

    private function insertDataInCache($keyCache, array $data): void
    {
        Cache::forget($keyCache);
        Cache::put("$keyCache", $data, EnvApp::$TIME_IN_CACHE);
    }
}
