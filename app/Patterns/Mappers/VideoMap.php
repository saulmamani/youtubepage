<?php

namespace App\Patterns\Mappers;

class VideoMap
{
    public static function mapData($items){
        $videos = [];
        foreach ($items as $item) {
            $videos[] = [
                "id" => $item['id'],
                "kind" => "video",
                "title" => $item['snippet']['title'],
                "publishedAt" => $item['snippet']['publishedAt'],
                "description" => $item['snippet']['description'],
                "image" => $item['snippet']['thumbnails']['standard']['url'],
                'channelId' => $item['snippet']['channelId'],
                'viewCount' => $item['statistics']['viewCount'],
                'likeCount' => $item['statistics']['likeCount'],
                'commentCount' => $item['statistics']['commentCount']
            ];
        };
        return $videos;
    }
}
