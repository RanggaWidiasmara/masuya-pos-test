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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_no')->unique(); // Auto-generate INV/YYMM/0001 [cite: 16, 37]
        $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete(); // Restrict delete [cite: 13]
        $table->date('transaction_date');
        $table->decimal('total_amount', 15, 2)->default(0); // Total header [cite: 19]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
