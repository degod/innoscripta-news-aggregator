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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->char('source_uuid', 36);
            $table->string('author')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('url')->unique();
            $table->string('url_to_image')->nullable();
            $table->dateTime('published_at');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('source_uuid')->references('uuid')->on('sources')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
