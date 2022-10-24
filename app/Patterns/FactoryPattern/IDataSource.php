<?php

namespace App\Patterns\FactoryPattern;

interface IDataSource
{
    public function videos($key, $q);
    public function playListVideos($kind, $id);
    public function videoDetail($kind, $id);
    public function comments($kind, $videoId);
}
