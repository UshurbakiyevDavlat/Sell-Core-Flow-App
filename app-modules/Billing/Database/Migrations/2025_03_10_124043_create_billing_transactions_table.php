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
        Schema::create('billing_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_account_id')->constrained()->onDelete('cascade'); // Привязка к балансу
            $table->decimal('amount', 20, 8); // Сумма операции
            $table->enum('type', ['credit', 'debit']); // Тип операции
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade'); // Привязка к ордеру (если есть)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_transactions');
    }
};
