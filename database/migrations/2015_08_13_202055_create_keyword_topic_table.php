<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_topic', function(Blueprint $table)
        {
            $table->integer('topic_id')->unsigned();
            $table->integer('keyword_id')->unsigned();

            $table->foreign('topic_id')->references('id')->on('topics')
                ->onDelete('cascade');

            $table->foreign('keyword_id')->references('id')->on('keywords')
                ->onDelete('cascade');
				
			$table->primary(['keyword_id', 'topic_id']);

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
        Schema::drop('keyword_topic');
    }
}
