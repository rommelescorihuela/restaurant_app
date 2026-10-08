<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build(): self
    {
        return $this->subject('Confirmación de reserva')
            ->markdown('emails.reservations.confirmation', [
                'reservation' => $this->reservation,
            ]);
    }
}
