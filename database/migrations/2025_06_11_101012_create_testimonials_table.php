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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('review');
            $table->string('image_url')->nullable();
            $table->tinyInteger('rating')->default(5);
            $table->uuid('token')->unique();
            $table->boolean('submitted')->default(false);
            $table->timestamp('token_created_at')->nullable();
            $table->unsignedBigInteger('client_id')->nullable(); // Add client/appointment reference
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
