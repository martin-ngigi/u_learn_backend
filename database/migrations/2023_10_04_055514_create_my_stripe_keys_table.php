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
        Schema::create('my_stripe_keys', function (Blueprint $table) {
            $table->id();
            $table->text('publishable_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->string('live_or_sandbox', 30)->nullable();
            $table->string('username', 100)->nullable();
            $table->string('password', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_stripe_keys');
    }
};
