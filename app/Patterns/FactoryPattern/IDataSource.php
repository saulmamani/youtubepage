<?php

namespace App\Patterns\FactoryPattern;

interface IDataSource
{
    public function videos($key, $q);
    public function playListVideos($id);
    public function videoDetail($id);
    public function comments($videoId);
}
