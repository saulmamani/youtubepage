<?php

namespace App\Http\Controllers;

use App\Patterns\FactoryPattern\IDataSource;
use App\Patterns\FactoryPattern\LocalData;
use App\Patterns\FactoryPattern\YoutubeData;
use App\Patterns\FactoryPattern\DataFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoController extends Controller
{
    protected DataFactory $dataFactory;
    public function __construct(DataFactory $dataFactory){
        $this->dataFactory = $dataFactory;
    }

    public function search(Request $request)
    {
        $searchKey = "q";
        $searchValue = $request->input('query');

        $source = $this->dataFactory->getDataSource($searchKey, $searchValue);

        return $source->videos($searchKey, $searchValue);
    }

    public function channelVideos(Request $request)
    {
        $searchKey = "channelId";
        $searchValue = $request->input('channelId');

        $source = $this->dataFactory->getDataSource($searchKey, $searchValue);

        return $source->videos($searchKey, $searchValue);
    }

    public function forceChannelYoutubeVideos(Request $request)
    {
        $searchKey = "channelId";
        $searchValue = $request->input('channelId');

        $source = new YoutubeData();
        return $source->videos($searchKey, $searchValue);
    }

    public function playlistVideos(Request $request){
        $kind = "playlistId";
        $playlistId = $request->input('playlistId');

        $source = $this->dataFactory->getDataSource($kind, $playlistId);

        return $source->playListVideos($kind, $playlistId);
    }

    public function videoDetail(Request $request){
        $kind = "video";
        $videoId = $request->input('videoId');

        $source = $this->dataFactory->getDataSource($kind, $videoId);

        return $source->videoDetail($kind, $videoId);
    }

    public function comments(Request $request){
        $kind = "comments";
        $videoId = $request->input('videoId');

        $source = $this->dataFactory->getDataSource($kind, $videoId);

        return $source->comments($kind, $videoId);
    }
}
