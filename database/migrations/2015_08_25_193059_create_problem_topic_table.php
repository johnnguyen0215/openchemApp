<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_topic', function (Blueprint $table) {
            $table->integer('topic_id')->unsigned()->index();
			$table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
			
			$table->integer('problem_id')->unsigned()->index();
			$table->foreign('problem_id')->references('id')->on('problems')->onDelete('cascade');
			
			$table->primary(['problem_id', 'topic_id']);
			
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
        Schema::drop('problem_topic');
    }
}
