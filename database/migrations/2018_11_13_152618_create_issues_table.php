<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('issue_id')->unsigned();

            $table->integer('repository_id')->unsigned();
            $table->foreign('repository_id')->references('id')->on('repositories');

            $table->string('title');
            $table->tinyInteger('priority');
            $table->tinyInteger('type');
            $table->tinyInteger('status');

            $table->string('username')->nullable();
            $table->date('date')->nullable();

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
        Schema::dropIfExists('issues');
    }
}
