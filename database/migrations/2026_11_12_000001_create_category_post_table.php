<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Erstellt die Pivot-Tabelle für die Mehrfachzuordnung von Beiträgen zu Rubriken (Kategorien).
     */
    public function up(): void
    {
        Schema::create('category_post', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->unique(['category_id', 'post_id']);
        });
    }

    /**
     * Macht die Migration rückgängig.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_post');
    }
};
