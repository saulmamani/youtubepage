<?php

namespace App\Patterns\FactoryPattern;

class LocalData implements IDataSource
{
    public function getVideos($key, $q)
    {
        //todo recoger datos del cache
        //todo si devuelve vacio, llamar al api de youtube para que actualice

       return ["get videos local data $key -> $q"];
    }
}
