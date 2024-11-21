<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('board_rates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('target_table', 30)->comment('table name');
            $table->bigInteger('target_id')->index()->comment('target id');
            $table->boolean('rate')->default(0)->comment('추천 여부');
            $table->foreignId('user_id')->index()->comment('users id');
            $table->bigInteger('target_user_id')->index()->comment('target users id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_rates');
    }
};
