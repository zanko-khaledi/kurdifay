<?php

namespace App\Models;

use App\Services\FileUploader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;

class Artist extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function posts(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Post::class,"postable");
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class,"tagable");
    }
}
