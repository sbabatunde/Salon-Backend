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
        // database/migrations/xxxx_xx_xx_create_signature_looks_table.php
        Schema::create('signature_looks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('tag');
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');  // status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature_looks');
    }
};
