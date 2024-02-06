<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('parent_id')->comment('商家分类父级ID');
            $table->string('path')->comment('分类路径');
            $table->string('title')->comment('分类名称');
            $table->string('meta_title')->comment('分类元标题');
            $table->string('meta_keywords')->nullable()->comment('分类关键词');
            $table->string('meta_description')->nullable()->comment('分类描述');
            $table->string('image')->nullable()->comment('分类图片');
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
        Schema::dropIfExists('categories');
    }
}
