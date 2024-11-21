<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('board_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('target_table', 50)->nullable()->comment('테이블명');
            $table->integer('target_id')->comment('대상 인덱스');
            $table->foreignId('user_id')->nullable()->index()->unsigned()->comment('회원 인덱스');
            $table->string('ip', 15)->comment('아이피');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
