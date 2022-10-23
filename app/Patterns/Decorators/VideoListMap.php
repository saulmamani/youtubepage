<?php

namespace App\Patterns\Decorators;

class VideoListMap
{
    public static function mapData($data){
        $listVideos = [];
        foreach ($data as $item) {
            $kind = self::getKind($item['id']);
            $id = self::getId($item['id'], $kind);

            $listVideos[] = [
                "id" => $id,
                "kind" => $kind,
                "title" => $item['snippet']['title'],
                "publishedAt" => $item['snippet']['publishedAt'],
                "description" => $item['snippet']['description'],
                "imageThumbnail" => $item['snippet']['thumbnails']['default']['url'],
                "image" => $item['snippet']['thumbnails']['medium']['url'],
                'channelId' => $item['snippet']['channelId']
            ];
        };
        return $listVideos;
    }

    private static function getKind($itemId){
       return explode("#", $itemId['kind'])[1];
    }

    private static function getId($itemId, $kind)
    {
        return  $itemId["{$kind}Id"];
    }
}
