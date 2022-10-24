<?php

namespace App\Patterns\Mappers;

class CommentMap
{
    public static function mapData($items){
        $comments = [];
        foreach ($items as $item) {
            $snippet = $item['snippet']['topLevelComment']['snippet'];
            $comments[] = [
                "id" => $item['id'],
                "kind" => "commnet",
                "textDisplay" => $snippet['textDisplay'],
                "authorDisplayName" => $snippet['authorDisplayName'],
                "authorProfileImageUrl" => $snippet['authorProfileImageUrl'],
                "likeCount" => $snippet['likeCount'],
            ];
        };
        return $comments;
    }
}
