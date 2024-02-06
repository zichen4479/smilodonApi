<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->unsigned()->comment('分类ID');
            $table->string('title')->comment('文章标题');
            $table->string('meta_title')->comment('文章标题');
            $table->string('meta_keywords')->comment('文章关键词');
            $table->string('meta_description')->comment('文章元描述');
            $table->text('description')->comment('内容');
            $table->integer('thumb')->comment('文章缩略图');
            $table->string('video_url')->comment('视频地址');
            $table->softDeletes();
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
        Schema::dropIfExists('articles');
    }
}
