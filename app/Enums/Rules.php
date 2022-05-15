<?php

namespace App\Enums;

enum Rules
{

    case ADMIN ;

    case USER;

    public function getRules():string
    {
        return  match ($this){
            Rules::ADMIN => "admin",
            Rules::USER => "user"
        };
    }
}
