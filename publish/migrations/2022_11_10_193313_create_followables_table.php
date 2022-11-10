<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateFollowablesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('follow.followables_table', 'followables'), function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger(config('follow.user_foreign_key', 'user_id'))->index()->comment('user_id');
          
            $table->morphs('followable');

            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->index(['followable_type', 'accepted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followables');
    }
}
