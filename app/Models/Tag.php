<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class,"tagable");
    }

    public function albums(): MorphToMany
    {
        return $this->morphedByMany(Album::class,"tagable");
    }

    public function artists(): MorphToMany
    {
        return $this->morphedByMany(Artist::class,"tagable");
    }
}
