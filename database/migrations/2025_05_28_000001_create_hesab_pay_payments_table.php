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
        Schema::create('hesab_pay_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('phone_number');
            $table->string('tracking_code')->unique();
            $table->string('session_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('status');
            $table->text('description')->nullable();
            $table->text('response_data')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hesab_pay_payments');
    }
}; 