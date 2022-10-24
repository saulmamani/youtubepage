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

    public function playListVideos($kind, $id){
        $keyCache = "{$kind}_{$id}";
        return Cache::get("$keyCache");
    }

    public function videoDetail($kind, $id){
        $keyCache = "{$kind}_{$id}";
        return Cache::get("$keyCache");
    }

    public function comments($kind, $videoId)
    {
        $keyCache = "{$kind}_{$videoId}";
        return Cache::get("$keyCache");
    }
}
