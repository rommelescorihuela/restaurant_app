<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->string('number')->unique();
            $table->integer('capacity');
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('zone_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('help_requested_at')->nullable();
            $table->boolean('alerted_abandoned')->default(false);
            $table->foreignId('merged_into_id')->nullable()->constrained('tables')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
        Schema::dropIfExists('zones');
    }
};