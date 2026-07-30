<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->enum('type', ['string', 'integer', 'float', 'boolean', 'json'])->default('string');
            $table->text('description')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Insert default settings
        $settings = [
            [
                'key' => 'domestic_transfer_fee',
                'value' => '2',
                'type' => 'float',
                'description' => 'Commission rate for domestic transfers (within Afghanistan)',
                'group' => 'commissions'
            ],
            [
                'key' => 'neighboring_transfer_fee',
                'value' => '3',
                'type' => 'float',
                'description' => 'Commission rate for transfers to neighboring countries',
                'group' => 'commissions'
            ],
            [
                'key' => 'international_transfer_fee',
                'value' => '5',
                'type' => 'float',
                'description' => 'Commission rate for international transfers',
                'group' => 'commissions'
            ],
            [
                'key' => 'dollar_to_afghani_fee',
                'value' => '0.5',
                'type' => 'float',
                'description' => 'Commission rate for converting dollars to afghanis',
                'group' => 'commissions'
            ],
            [
                'key' => 'afghani_to_dollar_fee',
                'value' => '1',
                'type' => 'float',
                'description' => 'Commission rate for converting afghanis to dollars',
                'group' => 'commissions'
            ],
            [
                'key' => 'minimum_transfer_amount',
                'value' => '10',
                'type' => 'float',
                'description' => 'Minimum amount for money transfers in USD',
                'group' => 'limits'
            ],
            [
                'key' => 'maximum_transfer_amount',
                'value' => '10000',
                'type' => 'float',
                'description' => 'Maximum amount for money transfers in USD',
                'group' => 'limits'
            ],
            [
                'key' => 'afn_to_usd_rate',
                'value' => '0.012',
                'type' => 'float',
                'description' => 'Exchange rate from AFN to USD',
                'group' => 'exchange_rates'
            ],
            [
                'key' => 'usd_to_afn_rate',
                'value' => '83.5',
                'type' => 'float',
                'description' => 'Exchange rate from USD to AFN',
                'group' => 'exchange_rates'
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->insert($setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
