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
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('code', 50)->unique(); // Alphanumeric, unique [cite: 10, 12, 36]
        $table->string('name');
        $table->text('address'); // Alamat jalan
        $table->string('province');
        $table->string('city');
        $table->string('district'); // Kecamatan
        $table->string('village'); // Kelurahan
        $table->string('zip_code', 10); // Kode pos
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
