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
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('intro_id')->after('thumbnail_id')->nullable()->constrained('media');
            $table->foreignId('poster_id')->after('intro_id')->nullable()->constrained('media');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_intro_id_foreign');
            $table->dropForeign('courses_poster_id_foreign');
            $table->dropColumn(['poster_id','intro_id']);
        });
    }
};
