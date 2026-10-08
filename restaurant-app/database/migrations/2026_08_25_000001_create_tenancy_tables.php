<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->json('data')->nullable();
        });

        Schema::create('domains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain', 255)->unique();
            $table->string('restaurant_id');
            $table->timestamps();
            $table->foreign('restaurant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id');
            $table->string('plan')->default('free_trial');
            $table->string('status')->default('trialing');
            $table->timestamp('trial_ends_at')->nullable();
            $table->date('current_period_start')->nullable();
            $table->date('current_period_end')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedInteger('seats_limit')->nullable();
            $table->timestamps();

            $table->foreign('restaurant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->unique('restaurant_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('domains');
        Schema::dropIfExists('tenants');
    }
};