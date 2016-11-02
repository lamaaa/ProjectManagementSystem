<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id')->unique();     // 项目ID
            $table->timestamps();                   // 创建时间和修改时间
            $table->string('name');                 // 项目名称
            $table->string('affiliation');          // 所属单位
            $table->integer('priority');            // 优先级
            $table->float('quote');                 // 现报价
            $table->string('stage');                // 进度
            $table->timestamp('deadline');          // 截止时间
            $table->timestamp('completion_time');   // 完成时间
            $table->string('project_type');         // 项目类型
            $table->text('requirements');           // 客户需求
            $table->integer('status');              // 进度
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('projects');
    }
}
