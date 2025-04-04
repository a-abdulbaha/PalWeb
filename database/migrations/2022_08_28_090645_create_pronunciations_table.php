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
        Schema::create('pronunciations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('translit')->collation('utf8mb4_0900_as_ci');
            $table->string('phonemic')->nullable();
            $table->string('phonetic')->nullable();
            $table->foreignId('dialect_id')->nullable()->constrained();
            $table->boolean('borrowed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pronunciations');
    }
};
