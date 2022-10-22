<?php

namespace App\Http\Controllers;

use App\Patterns\EnvApp;
use App\Patterns\FactoryPattern\IDataSource;
use App\Patterns\FactoryPattern\LocalData;
use App\Patterns\FactoryPattern\YoutubeData;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    protected IDataSource $source;

    public function __construct()
    {
        $this->source = (CacheDateTime::isNecesaryRefreshFromYoutube()) ?
            new YoutubeData() : new LocalData();
    }

    public function search(Request $request)
    {
        $searchKey = "q";
        $searchValue = $request->input('query');
        return $this->source->getVideos($searchKey, $searchValue);
    }

    public function channelVideos(Request $request){
        $searchKey = "channelId";
        $searchValue = $request->input('channelId');
        return $this->source->getVideos($searchKey, $searchValue);
    }

    public function forceChannelYoutubeVideos(Request $request)
    {
        $searchKey = "channelId";
        $searchValue = $request->input('channelId');

        $this->source = new YoutubeData();
        $videos = $this->source->getVideos($searchKey, $searchValue);

        //TODO aqui actualizar la base de datos local
        CacheDateTime::setDateToNextRefresh();

        return $videos;
    }
}
