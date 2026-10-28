<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Erstellt die Tabelle für Beitragskategorien (z. B. Ausflug, Fest, Projekt).
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('color', 24)->default('#14b8a6');
            $table->timestamps();
        });
    }

    /**
     * Löscht die Kategorientabelle.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
