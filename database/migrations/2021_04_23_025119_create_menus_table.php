<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id')->unsigned()->comment('网站ID');
            $table->integer('menu_type')->unsigned()->comment('菜单类型: 1、分类，2、单页面 3、文章');
            $table->string('menu_title')->comment('菜单名称');
            $table->integer('relation_id')->unsigned()->comment('关联的ID');
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
        Schema::dropIfExists('menus');
    }
}
