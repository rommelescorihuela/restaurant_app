<?php

namespace App\Http\Controllers;

use App\Mail\ReservationConfirmation;
use App\Models\Customer;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicReservationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
            'guests' => 'required|integer|min:1|max:20',
        ]);

        $customer = Customer::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]
        );

        $reservation = Reservation::create([
            'customer_id' => $customer->id,
            'reservation_date' => Carbon::parse($validated['date'] . ' ' . $validated['time']),
            'guest_count' => $validated['guests'],
            'status' => 'pending',
            'notes' => null,
        ]);

        Mail::to($customer->email)->send(new ReservationConfirmation($reservation));

        return redirect()->back()->with('success', 'Reserva confirmada. Te esperamos!');
    }
}
