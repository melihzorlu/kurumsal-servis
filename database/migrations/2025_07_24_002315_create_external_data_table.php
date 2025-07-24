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
        Schema::create('external_data', function (Blueprint $table) {
            $table->id();
            $table->string('source');          // REST veya SOAP gibi kaynak türü
            $table->string('external_id')->nullable(); // dış sistemdeki ID varsa
            $table->json('raw_data');         // normalize edilmemiş ham veri
            $table->json('normalized_data');  // bizim normalize ettiğimiz hali
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_data');
    }
};
