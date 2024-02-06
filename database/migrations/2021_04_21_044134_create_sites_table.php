<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->integer('system_setting_language_id')->unsigned()->comment('语言ID');
            $table->string('site_name')->comment('站点名称');
            $table->string('meta_title')->comment('站点元标题');
            $table->string('meta_keywords')->comment('站点关键词');
            $table->string('meta_description')->comment('站点元描述');
            $table->string('logo')->comment('Logo');
            $table->string('mail_host')->comment('邮件SMTP主机');
            $table->string('mail_port')->comment('邮件端口号');
            $table->string('mail_username')->comment('邮件用户名');
            $table->string('mail_password')->comment('邮件密码');
            $table->string('receive_mail')->comment('接收邮箱');
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
        Schema::dropIfExists('sites');
    }
}
