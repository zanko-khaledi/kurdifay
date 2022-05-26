<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface IFile
{

    public static function img(Request $request);


    public static function song(Request $request);
}
