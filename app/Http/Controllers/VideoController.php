<?php

namespace App\Http\Controllers;

use App\Patterns\EnvApp;
use App\Patterns\FactoryPattern\IDataSource;
use App\Patterns\FactoryPattern\LocalData;
use App\Patterns\FactoryPattern\YoutubeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoController extends Controller
{
    protected IDataSource $source;

    public function search(Request $request)
    {
        $searchKey = "q";
        $searchValue = $request->input('query');

        $this->source = $this->getDataSource($searchKey, $searchValue);

        return $this->source->videos($searchKey, $searchValue);
    }

    public function channelVideos(Request $request)
    {
        $searchKey = "channelId";
        $searchValue = $request->input('channelId');

        $this->source = $this->getDataSource($searchKey, $searchValue);

        return $this->source->videos($searchKey, $searchValue);
    }

    public function forceChannelYoutubeVideos(Request $request)
    {
        $searchKey = "channelId";
        $searchValue = $request->input('channelId');

        $this->source = new YoutubeData();
        return $this->source->videos($searchKey, $searchValue);
    }

    public function playlistVideos(Request $request){
        $kind = "playlistId";
        $playlistId = $request->input('playlistId');

        $this->source = $this->getDataSource($kind, $playlistId);

        return $this->source->playListVideos($kind, $playlistId);
    }

    public function videoDetail(Request $request){
        $kind = "video";
        $videoId = $request->input('videoId');

        $this->source = $this->getDataSource($kind, $videoId);

        return $this->source->videoDetail($kind, $videoId);
    }

    public function comments(Request $request){
        $kind = "comments";
        $videoId = $request->input('videoId');
        
        $this->source = $this->getDataSource($kind, $videoId);

        return $this->source->comments($kind, $videoId);
    }

    private function getDataSource(string $kind, string $value): IDataSource
    {
        $keyCache = "{$kind}_{$value}";
        return Cache::has($keyCache) ? new LocalData() : new YoutubeData();
    }
}
