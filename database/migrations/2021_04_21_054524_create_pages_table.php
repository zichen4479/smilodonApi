<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->integer('system_setting_language_id')->unsigned()->comment('语言ID');
            $table->string('title')->nullable()->comment('页面标题');
            $table->string('meta_title')->nullable()->comment('页面元标题');
            $table->string('meta_keywords')->nullable()->comment('页面关键词');
            $table->string('meta_description')->nullable()->comment('页面元描述');
            $table->text('description')->nullable()->comment('页面内容');
            $table->string('image')->nullable()->comment('图片');
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
        Schema::dropIfExists('pages');
    }
}
