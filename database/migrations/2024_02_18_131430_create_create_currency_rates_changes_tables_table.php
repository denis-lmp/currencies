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
        Schema::create('currency_rates_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('resource_type'); // You might need to adjust this depending on how you store resource types
            $table->decimal('previous_rate', 12, 6);
            $table->decimal('current_rate', 12, 6);
            $table->decimal('change_percent', 8, 2);
            $table->timestamp('change_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_currency_rates_changes_tables');
    }
};
