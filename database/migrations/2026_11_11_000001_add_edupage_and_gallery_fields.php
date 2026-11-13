<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Erweitert Seiten, Termine, Beiträge und Fotoalben um EduPage-Portal- und Galerie-Felder.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->string('menu_group', 100)->nullable()->after('section');
        });

        Schema::table('events', function (Blueprint $table): void {
            $table->string('class_name', 50)->nullable()->after('location');
            $table->string('category', 50)->default('Termin')->after('class_name');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->json('gallery_images')->nullable()->after('album_id');
        });

        Schema::table('albums', function (Blueprint $table): void {
            $table->json('gallery_images')->nullable()->after('description');
        });
    }

    /**
     * Macht die Schema-Erweiterungen rückgängig.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn('menu_group');
        });

        Schema::table('events', function (Blueprint $table): void {
            $table->dropColumn(['class_name', 'category']);
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn('gallery_images');
        });

        Schema::table('albums', function (Blueprint $table): void {
            $table->dropColumn('gallery_images');
        });
    }
};
