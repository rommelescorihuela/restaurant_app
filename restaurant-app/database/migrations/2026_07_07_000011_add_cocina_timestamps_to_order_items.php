<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('status');
            $table->timestamp('prepared_at')->nullable()->after('started_at');
            $table->timestamp('cancelled_at')->nullable()->after('prepared_at');
            $table->text('cancel_reason')->nullable()->after('cancelled_at');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('priority')->default('normal')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'prepared_at', 'cancelled_at', 'cancel_reason']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
};
