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
        Schema::table('movies', function (Blueprint $table) {
            $table->string('title')->unique()->change();
            $table->string('image_url')->change();
            $table->foreign('genre_id')->references('id')->on('genres');
            $table->integer('published_year')->change();
            $table->boolean('is_showing')->default(false)->change();
            $table->text('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropUnique(['title']);
            $table->dropColumn(['image_url', 'published_year', 'is_showing', 'description']);
        });
    }
};
