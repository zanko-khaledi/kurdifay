<?php

namespace App\Enums;

enum Entities
{
   case SONG;

   case PODCAST;

   case BLOG;

   case ABOUT_US;

   public function getEntity():string
   {
       return match ($this){
           Entities::SONG => "song",
           Entities::BLOG => "blog",
           Entities::PODCAST => "podcast",
           Entities::ABOUT_US => "about_us"
       };
   }
}
