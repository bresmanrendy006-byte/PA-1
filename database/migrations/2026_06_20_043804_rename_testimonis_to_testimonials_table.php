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
        Schema::rename('testimonis', 'testimonials');

        Schema::table('testimonials', function (Blueprint $table) {
            $table->renameColumn('isi', 'content');
            $table->renameColumn('foto', 'photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->renameColumn('content', 'isi');
            $table->renameColumn('photo', 'foto');
        });

        Schema::rename('testimonials', 'testimonis');
    }
};