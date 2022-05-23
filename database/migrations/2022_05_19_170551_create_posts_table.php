<?php

use App\Enums\Entities;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("subcategory_id")->constrained("subcategories")
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("title",128);
            $table->text("desc");
            $table->string("slug",128);
            $table->string("artist",128)->nullable(true);
            $table->enum("entity",[Entities::SONG->getEntity(),Entities::BLOG->getEntity(),Entities::ABOUT_US->getEntity(),Entities::PODCAST->getEntity()])
                ->default(Entities::SONG->getEntity());
            $table->string("img",128)->nullable(true);
            $table->text("lyric")->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
