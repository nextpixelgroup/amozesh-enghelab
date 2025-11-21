<?php

use App\Enums\GenderEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable()->index();
            $table->string('lastname')->nullable()->index();
            $table->string('username')->nullable();
            $table->string('slug',100)->nullable()->unique()->index();
            $table->string('mobile', 11)->nullable()->index();
            $table->string('tel', 20)->nullable();
            $table->string('email')->nullable()->index();
            $table->date('birth_date')->nullable();
            $table->enum('gender', enumNames(GenderEnum::cases()))->index()->nullable();
            $table->string('national_code', 10)->index()->unique()->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->text('address')->nullable();
            $table->string('company')->nullable(); // سازمان یا شرکت محل فعالیت
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('institution_id')->nullable()->index()->constrained('users');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
