<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $tables = Table::all();

        if ($customers->isEmpty() || $tables->isEmpty()) {
            return;
        }

        $reservations = [
            ['days_from_now' => 0, 'time' => '13:00', 'guest_count' => 2, 'table_index' => 0, 'status' => 'confirmed'],
            ['days_from_now' => 0, 'time' => '14:00', 'guest_count' => 4, 'table_index' => 1, 'status' => 'confirmed'],
            ['days_from_now' => 1, 'time' => '20:00', 'guest_count' => 6, 'table_index' => 2, 'status' => 'pending'],
            ['days_from_now' => 1, 'time' => '21:00', 'guest_count' => 2, 'table_index' => 3, 'status' => 'pending'],
            ['days_from_now' => -5, 'time' => '19:00', 'guest_count' => 4, 'table_index' => 4, 'status' => 'completed'],
            ['days_from_now' => -3, 'time' => '20:30', 'guest_count' => 8, 'table_index' => 5, 'status' => 'completed'],
            ['days_from_now' => -10, 'time' => '13:30', 'guest_count' => 2, 'table_index' => 6, 'status' => 'cancelled'],
            ['days_from_now' => 3, 'time' => '20:00', 'guest_count' => 4, 'table_index' => 7, 'status' => 'confirmed'],
            ['days_from_now' => 7, 'time' => '19:30', 'guest_count' => 3, 'table_index' => 8, 'status' => 'pending'],
            ['days_from_now' => 14, 'time' => '21:00', 'guest_count' => 5, 'table_index' => 9, 'status' => 'pending'],
        ];

        foreach ($reservations as $index => $r) {
            $customer = $customers->values()[$index % $customers->count()];
            $table = $tables[$r['table_index']] ?? $tables->random();
            $reservationDate = Carbon::today()->addDays($r['days_from_now'])->setTimeFromTimeString($r['time']);

            Reservation::firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'table_id' => $table->id,
                    'reservation_date' => $reservationDate,
                ],
                [
                    'guest_count' => $r['guest_count'],
                    'status' => $r['status'],
                ]
            );
        }
    }
}