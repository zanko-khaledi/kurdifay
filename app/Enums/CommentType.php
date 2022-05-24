<?php

namespace App\Enums;

enum CommentType
{

    case COMMENT;

    case REPLAY;

    public function getType():string
    {
        return  match ($this){
            CommentType::COMMENT => "comment",
            CommentType::REPLAY => "replay"
        };
    }
}
