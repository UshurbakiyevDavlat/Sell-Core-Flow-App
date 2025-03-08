<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('asset_id')->comment('Актив')->constrained('assets');
            $table->string('type')->comment('Тип ордера: market or limit');
            $table->string('side')->comment('Сторона: buy or sell');
            $table->decimal('price', 16, 2)->nullable()->comment('Цена, только для лимит ордеров');
            $table->decimal('quantity', 16, 6)->comment('Кол-во актива(сколько покупаем или продаем)');
            $table->string('status')->default('pending')->comment('pending,executed,cancelled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
