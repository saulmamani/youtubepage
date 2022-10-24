<?php

namespace App\Patterns\FactoryPattern;

use Illuminate\Support\Facades\Cache;

class LocalData implements IDataSource
{
    public function videos($key, $q)
    {
        $keyCache = "{$key}_{$q}";
        return Cache::get($keyCache);
    }

    public function playListVideos($id){
        return Cache::get("playlist_$id");
    }

    public function videoDetail($id){
        return Cache::get("video_$id");
    }

    public function comments($videoId)
    {
        return Cache::get("comments_$videoId");
    }
}
