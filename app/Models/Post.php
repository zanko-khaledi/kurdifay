<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function song(): HasOne
    {
        return $this->hasOne(Song::class);
    }

    public function album(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphedByMany(Album::class,"postable");
    }

    public function artist(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphedByMany(Artist::class,"postable");
    }


    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Tag::class,"tagable");
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
