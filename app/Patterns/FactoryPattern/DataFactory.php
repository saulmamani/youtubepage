<?php

namespace App\Patterns\FactoryPattern;

use Illuminate\Support\Facades\Cache;

class DataFactory {
    public function getDataSource(string $kind, string $value): IDataSource
    {
        $keyCache = "{$kind}_{$value}";
        if(Cache::has($keyCache))
            return new LocalData();
        else
            return new YoutubeData();
    }
}
