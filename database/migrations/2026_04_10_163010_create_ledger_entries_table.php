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
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_ledger_id')->constrained()->cascadeOnDelete();
            $table->date('entry_date');

            // Purchases
            $table->decimal('medicine_purchase_company', 12, 2)->default(0);
            $table->decimal('medicine_purchase_shop', 12, 2)->default(0);
            $table->decimal('medicine_purchase_other', 12, 2)->default(0);

            // Payments
            $table->decimal('payment_company', 12, 2)->default(0);
            $table->decimal('payment_shop', 12, 2)->default(0);
            $table->decimal('payment_other', 12, 2)->default(0);

            // Sales
            $table->decimal('daily_sale', 12, 2)->default(0);
            $table->decimal('hole_sale', 12, 2)->default(0);
            $table->decimal('other_sale', 12, 2)->default(0);
            $table->decimal('due_purchase', 12, 2)->default(0);
            $table->decimal('due_sale', 12, 2)->default(0);

            // Costs
            $table->decimal('daily_staff_cost', 12, 2)->default(0);
            $table->decimal('other_cost', 12, 2)->default(0);
            $table->decimal('salary', 12, 2)->default(0);
            $table->decimal('bill', 12, 2)->default(0);
            $table->decimal('rent', 12, 2)->default(0);

            $table->timestamps();

            $table->unique(['monthly_ledger_id', 'entry_date']);
            $table->index(['monthly_ledger_id', 'entry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
