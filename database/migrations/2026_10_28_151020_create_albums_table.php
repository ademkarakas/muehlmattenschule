<?php

declare(strict_types=1);

use App\Enums\PublishStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Erstellt die Tabelle für Fotoalben inklusive Pflichtfeld für DSGVO-Einwilligung.
     */
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->date('event_date')->nullable()->index();
            $table->text('description')->nullable();
            $table->boolean('consent_confirmed')->default(false);
            $table->string('status', 32)->default(PublishStatus::Draft->value)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Löscht die Fotoalbentabelle.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};

