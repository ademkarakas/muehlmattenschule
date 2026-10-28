<?php

declare(strict_types=1);

use App\Enums\PersonGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Erstellt die Tabelle für Personen (Schulleitung, Kollegium, Verwaltung).
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 160);
            $table->string('role_title', 160);
            $table->string('class_name', 60)->nullable();
            $table->string('email', 160)->nullable();
            $table->string('group', 40)->default(PersonGroup::Kollegium->value)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Löscht die Personentabelle.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
