<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Erstellt die Schlüssel-Wert-Tabelle für allgemeine Schuleinstellungen.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 120)->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Löscht die Einstellungstabelle.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

