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
        $keyCache = "{$searchKey}_{$searchValue}";

        $this->source = $this->getDataSource($keyCache);

        return $this->source->videos($searchKey, $searchValue);
    }

    public function channelVideos(Request $request)
    {
        $searchKey = "channelId";
        $searchValue = $request->input('channelId');
        $keyCache = "{$searchKey}_{$searchValue}";

        $this->source = $this->getDataSource($keyCache);

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
        $playlistId = $request->input('playlistId');
        $this->source = $this->getDataSource("playlist_$playlistId");

        return $this->source->playListVideos($playlistId);
    }

    public function videoDetail(Request $request){
        $videoId = $request->input('videoId');
        $this->source = $this->getDataSource("video_$videoId");

        return $this->source->videoDetail($videoId);
    }

    public function comments(Request $request){
        $videoId = $request->input('videoId');
        $this->source = $this->getDataSource("comments_$videoId");

        return $this->source->comments($videoId);
    }

    private function getDataSource(string $keyCache): IDataSource
    {
        return Cache::has($keyCache) ? new LocalData() : new YoutubeData();
    }
}
