<?php

namespace App\Patterns\FactoryPattern;

interface IDataSource
{
    public function getVideos($key, $q);
}
