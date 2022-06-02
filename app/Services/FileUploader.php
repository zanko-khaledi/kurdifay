<?php

namespace App\Services;

use App\Interfaces\IFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileUploader implements IFile
{

    /**
     * @param Request $request
     * @return string|null
     */
    public static function img(Request $request): ?string
    {
        $name = $request->title ? : $request->name;
        $file = $request->file("img");
        $file_name = $name.'_'.Str::random().'.'.$file->getClientOriginalExtension();

        return $file->move(public_path("/files"),$file_name) ?
            asset("/files/".$file_name) : null;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public static function song(Request $request): ?string
    {
        $title = $request->title;
        $file = $request->file("src");
        $file_name = $title.'_'.Str::random().'.'.$file->getClientOriginalExtension();

        return $file->move(public_path("/songs"),$file_name) ?
            asset("/songs/".$file_name) : null;
    }
}
