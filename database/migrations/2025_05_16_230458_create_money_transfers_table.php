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
        Schema::create('money_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('sender_name');
            $table->string('sender_telegram');
            $table->string('source_country');
            $table->string('destination_country');
            $table->string('destination_city_province');
            $table->string('recipient_name');
            $table->string('recipient_id_passport');
            $table->decimal('amount_usd', 12, 2);
            $table->decimal('commission_amount', 12, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->enum('payment_method', ['dollar_wallet', 'agency_visit']);
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected', 'cancelled'])->default('pending');
            $table->string('tracking_number')->unique();
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('support_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();
            $table->boolean('is_domestic')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_transfers');
    }
};
