<?php

declare(strict_types=1);

use App\Enums\AnnouncementLevel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Erstellt die Tabelle für zeitgesteuerte Ankündigungen auf der Startseite.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table): void {
            $table->id();
            $table->string('text', 500);
            $table->string('level', 24)->default(AnnouncementLevel::Info->value);
            $table->string('link_url', 300)->nullable();
            $table->string('link_label', 100)->nullable();
            $table->timestamp('visible_from')->nullable()->index();
            $table->timestamp('visible_until')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Löscht die Ankündigungstabelle.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

