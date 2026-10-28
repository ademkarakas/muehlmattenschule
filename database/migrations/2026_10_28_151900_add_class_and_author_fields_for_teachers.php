<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ergänzt Klassenfelder für Lehrkräfte sowie Urheber- und Klassenzuordnung bei Fotoalben und Beiträgen.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('class_name', 60)->nullable()->after('role');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->string('class_name', 60)->nullable()->after('category_id')->index();
        });

        Schema::table('albums', function (Blueprint $table): void {
            $table->string('class_name', 60)->nullable()->after('event_date')->index();
            $table->foreignId('author_id')->nullable()->after('class_name')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Macht die Spaltenänderungen rückgängig.
     */
    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('author_id');
            $table->dropColumn('class_name');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn('class_name');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('class_name');
        });
    }
};

