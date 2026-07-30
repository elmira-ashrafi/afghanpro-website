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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('afghan_wallet_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('dollar_wallet_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->enum('currency_type', ['AFN', 'USD']);
            $table->enum('transaction_type', ['deposit', 'withdraw', 'transfer', 'order', 'conversion', 'refund']);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('reference_id')->nullable(); // Can reference an order ID, transfer request ID, etc.
            $table->string('reference_type')->nullable(); // Model class name (e.g., Order::class, TransferRequest::class)
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
