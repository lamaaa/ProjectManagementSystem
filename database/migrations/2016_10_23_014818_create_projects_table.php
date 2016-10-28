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
            $table->increments('id')->unique();
            $table->timestamps();
            $table->string('name');
            $table->string('affiliation');
            $table->integer('priority');
            $table->float('quote');
            $table->string('stage');
            $table->timestamp('completion_time');
            $table->string('project_type');
            $table->string('project_situation');
            $table->integer('workforce');
            $table->text('requirements');
            $table->float('budget');
            $table->integer('status');
            $table->string('own_clound');
            $table->float('cost');
            $table->float('profit');
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
