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
        Schema::create('trade_account_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('lastname');
            $table->string('telegram_number');
            $table->string('broker_name');
            $table->string('city_province');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->enum('payment_method', ['dollar_wallet', 'agency_visit']);
            $table->string('trade_account_username')->nullable();
            $table->string('trade_account_password')->nullable();
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected', 'cancelled'])->default('pending');
            $table->string('tracking_code')->unique();
            $table->boolean('credentials_submitted')->default(false);
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('support_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_account_requests');
    }
};
