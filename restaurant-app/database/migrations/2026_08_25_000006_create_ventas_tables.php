<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('table_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('reservation_date');
            $table->integer('guest_count');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->foreignId('table_id')->constrained()->cascadeOnDelete();
            $table->foreignId('waiter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');
            $table->string('priority')->default('normal');
            $table->text('notes')->nullable();
            $table->text('internal_note')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dish_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->text('modifiers')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('prepared_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->text('notes')->nullable();
            $table->text('kitchen_note')->nullable();
            $table->text('return_reason')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->foreignId('table_id')->constrained()->cascadeOnDelete();
            $table->foreignId('waiter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->text('description');
            $table->string('status')->default('open');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('waste_records', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->foreignId('dish_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->foreignId('registered_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('table_histories', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id')->nullable()->index();
            $table->foreignId('table_id')->constrained()->cascadeOnDelete();
            $table->foreignId('waiter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_histories');
        Schema::dropIfExists('waste_records');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('reservations');
    }
};