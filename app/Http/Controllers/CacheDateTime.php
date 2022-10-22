<?php

namespace App\Http\Controllers;

use App\Patterns\EnvApp;
use DateInterval;
use DateTime;

class CacheDateTime
{
    private static function getDateNextRefresh()
    {
        //TODO recuperar desde la base de datos
        $nexDateReload = "2022-10-22 14:00:00";
        return EnvApp::setFechaHora($nexDateReload);
    }

    public static function setDateToNextRefresh(): void
    {
        $actualDate = EnvApp::getFechaHora();
        $actualDate->add(new DateInterval('PT0H5M30S'));
        //todo guardar la fecha en la base de datos local
        $actualDate->format("d/m/Y H:i:s");
    }

    public static function isNecesaryRefreshFromYoutube(){
        $actualDate = EnvApp::getFechaHora();
        $nextRefreshDate = self::getDateNextRefresh();

        return $actualDate > $nextRefreshDate;
    }
}
