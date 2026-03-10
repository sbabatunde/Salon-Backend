<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('appointments')->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('service_cost', 10, 2);
            $table->decimal('material_cost', 10, 2)->default(0);
            $table->decimal('other_cost', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Calculated columns
            // $table->decimal('total_cost', 10, 2)->virtualAs('service_cost + material_cost + other_cost');
            // $table->decimal('profit', 10, 2)->virtualAs('amount_paid - (service_cost + material_cost + other_cost)');
        });

        // Add generated columns after table creation (PostgreSQL way)
        DB::statement('
            ALTER TABLE accounts 
            ADD COLUMN total_cost decimal(10,2) GENERATED ALWAYS AS (service_cost + material_cost + other_cost) STORED
        ');

        DB::statement('
            ALTER TABLE accounts 
            ADD COLUMN profit decimal(10,2) GENERATED ALWAYS AS (amount_paid - (service_cost + material_cost + other_cost)) STORED
        ');
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
