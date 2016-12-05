<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChemtextTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chemtext_topic', function (Blueprint $table) {
            $table->integer('topic_id')->unsigned();
			$table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
			
			$table->integer('chemtext_id')->unsigned();
			$table->foreign('chemtext_id')->references('id')->on('chemtexts')->onDelete('cascade');
	
			$table->primary(['chemtext_id', 'topic_id']);
			
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
        Schema::drop('chemtext_topic');
    }
}
