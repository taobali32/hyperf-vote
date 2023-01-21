<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('vote.votes_table', 'votes'), function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger(config('vote.user_foreign_key', 'user_id'))->index()->comment('user_id');

            $table->integer('votes');

            $table->integer('votable_id');
            $table->integer('votable_type');


            $table->morphs('votable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('vote.votes_table'));
    }
}
