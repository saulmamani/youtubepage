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
        return Cache::get($id);
    }
}
