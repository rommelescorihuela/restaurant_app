<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->text('kitchen_note')->nullable()->after('notes');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->text('return_reason')->nullable()->after('kitchen_note');
            $table->timestamp('returned_at')->nullable()->after('return_reason');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['kitchen_note', 'return_reason', 'returned_at']);
        });
    }
};
