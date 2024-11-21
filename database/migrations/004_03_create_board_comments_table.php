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
        Schema::create('board_comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('write_id')->unsigned()->index()->comment('writes.id');
            $table->integer('parent_id')->index()->nullable()->comment('parent comments.id');
            $table->tinyInteger('depth')->nullable()->comment('depth');
            $table->integer('rate_up')->unsigned()->default(0)->comment('추천 수');
            $table->integer('rate_down')->unsigned()->default(0)->comment('비추천 수');
            $table->integer('report_count')->unsigned()->default(0)->comment('신고 수');
            $table->foreignId('user_id')->nullable()->index()->comment('users.id');
            $table->string('writer', 50)->nullable()->comment('작성자');
            $table->text('comment')->comment('댓글 내용');
            $table->boolean('is_delete')->default(0)->comment('삭제 여부');
            $table->timestamp('deleted_at')->nullable()->comment('삭제 시간');
            $table->string('ip', 15)->comment('작성자 아이피');

            $table->foreign('write_id')->references('id')->on('board_writes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_comments');
    }
};
