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
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ['HYRESE', 'DALESE', 'INTERNAL']);
            $table->string('outgoing_iban');
            $table->string('incoming_iban');
            $table->timestamp('transaction_date')->useCurrent();
            $table->decimal('amount', 15, 2);
            $table->enum('currency', ['ALL', 'EUR', 'USD', 'GBP']);
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_transactions');
    }
};
