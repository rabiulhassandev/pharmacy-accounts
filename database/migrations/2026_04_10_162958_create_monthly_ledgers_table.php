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
        Schema::create('monthly_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->smallInteger('year');
            $table->tinyInteger('month');
            $table->boolean('is_locked')->default(false);
            $table->decimal('previous_balance', 14, 2)->default(0);
            $table->timestamps();

            $table->unique(['year', 'month']);
            $table->index(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_ledgers');
    }
};
