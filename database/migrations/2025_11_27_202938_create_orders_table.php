<?php

use App\Enums\OrderStatusEnum;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('original_total');
            $table->unsignedBigInteger('discount_total');
            $table->unsignedBigInteger('shipping_cost');
            $table->unsignedBigInteger('total');
            $table->enum('status', enumNames(OrderStatusEnum::cases()))->default('pending');
            $table->string('reference_id')->nullable();
            $table->string('gateway')->nullable(); // sadad | wallet_topup | wallet | mixed
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->json('meta')->nullable();
            $table->text('canceled_reason')->nullable();
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
