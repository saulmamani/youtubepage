<?php

namespace App\Patterns;

class EnvApp
{
    public static int $TIME_LOCAL_DATA = 20;

    public static function getFechaHora()
    {
        $soloFecha = date('d/m/Y');
        $hora = date('H:i:s');
        $fecha = $soloFecha . ' ' . $hora;
        return \DateTime::createFromFormat('d/m/Y H:i:s', $fecha);
    }

    public static function setFechaHora($fecha)
    {
        $fecha = date("d/m/Y H:i:s", strtotime($fecha));
        return \DateTime::createFromFormat('d/m/Y H:i:s', $fecha);
    }
}
