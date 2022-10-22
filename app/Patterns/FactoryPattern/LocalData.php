<?php

namespace App\Patterns\FactoryPattern;

use Illuminate\Support\Facades\Cache;

class LocalData implements IDataSource
{
    public function getVideos($key, $q)
    {
        $keyCache = "{$key}_{$q}";
        return Cache::get($keyCache);
    }
}
