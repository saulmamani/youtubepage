<?php

namespace App\Patterns\FactoryPattern;

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

    public function getVideos($key, $q)
    {
        $params = [
            "$key" => $q,
            "part" => "snippet,id",
            "regionCode" => "BO",
            "maxResults" => "50"
        ];

        $response = $this->getHttp()->get("{$this->url}search", $params);
        $listVideos = $this->mapResponse($response['items']);

        $this->insertVideosInCache($key, $q, $listVideos);

        return $listVideos;
    }

    private function getKindId($id1): array
    {
        $kind = explode("#", $id1['kind'])[1];
        $id = $id1["{$kind}Id"];
        return array($kind, $id);
    }

    private function mapResponse($items): array
    {
        $listVideos = [];
        foreach ($items as $item) {
            list($kind, $id) = $this->getKindId($item['id']);

            $listVideos[] = [
                "id" => $id,
                "kind" => $kind,
                "title" => $item['snippet']['title'],
                "publishedAt" => $item['snippet']['publishedAt'],
                "description" => $item['snippet']['description'],
                "image" => $item['snippet']['thumbnails']['medium']['url'],
                'channelId' => $item['snippet']['channelId']
            ];
        };
        return $listVideos;
    }

    private function insertVideosInCache($key, $q, array $listVideos): void
    {
        $keyCache = "{$key}_{$q}";
        Cache::forget($keyCache);
        Cache::put("$keyCache", $listVideos, EnvApp::$TIME_IN_CACHE);
    }

    public function playListVideos($id)
    {
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
}
