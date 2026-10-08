<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waiter_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('waiter_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->foreignId('table_id')->constrained()->cascadeOnDelete();
            $table->foreignId('waiter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->string('status')->default('active');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('unassigned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('waiter_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->foreignId('waiter_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->boolean('is_on_break')->default(false);
            $table->timestamp('break_started_at')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waiter_shifts');
        Schema::dropIfExists('waiter_assignments');
        Schema::dropIfExists('waiter_profiles');
    }
};