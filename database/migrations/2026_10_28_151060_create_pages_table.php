<?php

declare(strict_types=1);

use App\Enums\PublishStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Erstellt die Tabelle für statische Inhaltsseiten (z. B. Schulportrait, Leitbild).
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->string('section', 64)->default('unsere-schule')->index();
            $table->unsignedSmallInteger('menu_order')->default(0);
            $table->string('summary', 400)->nullable();
            $table->json('content');
            $table->string('status', 32)->default(PublishStatus::Published->value)->index();
            $table->timestamps();
        });
    }

    /**
     * Löscht die Seitentabelle.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};

