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
        Schema::table('term_relative', function (Blueprint $table) {
            $table->foreignId('reciprocal_id')
                ->after('id')
                ->nullable()
                ->constrained('term_relative')
                ->cascadeOnUpdate()
                ->nullOnDelete();

//            $table->unique(
//                ['gloss_id', 'relative_id', 'type'],
//                'term_relative_unique_gloss_relative',
//            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('term_relative', function (Blueprint $table) {
//            $table->dropUnique('term_relative_unique_gloss_relative');
            $table->dropForeign(['reciprocal_id']);
            $table->dropColumn('reciprocal_id');
        });
    }
};
