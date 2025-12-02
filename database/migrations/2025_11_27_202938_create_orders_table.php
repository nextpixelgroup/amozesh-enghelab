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
            $table->string('fullname',100)->index()->nullable();
            $table->text('address')->nullable();
            $table->string('mobile',20)->index()->nullable();
            $table->string('email',50)->index()->nullable();
            $table->string('postal_code',20)->index()->nullable();
            $table->text('user_note')->nullable();
            $table->unsignedBigInteger('original_total');
            $table->unsignedBigInteger('discount_total');
            $table->unsignedBigInteger('shipping_cost');
            $table->unsignedBigInteger('total');
            $table->enum('status', enumNames(OrderStatusEnum::cases()))->default('pending');
            $table->unsignedBigInteger('reference_id')->index()->nullable();
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
