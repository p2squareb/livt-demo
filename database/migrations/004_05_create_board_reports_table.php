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
        Schema::create('board_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('target_table', 30)->comment('target table');
            $table->integer('target_id')->nullable()->index()->comment('target id');
            $table->integer('user_id')->nullable()->index()->comment('report.users.id');
            $table->integer('target_user_id')->nullable()->index()->comment('reportes.users.id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_reports');
    }
};
