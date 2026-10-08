@component('mail::message')
# Reserva confirmada

Hola {{ $reservation->customer->name }},

Tu reserva ha sido recibida:

- **Fecha:** {{ $reservation->reservation_date->format('d/m/Y H:i') }}
- **Personas:** {{ $reservation->guest_count }}

Te esperamos. Si necesitas cancelar o modificar, contáctanos.

Gracias por elegirnos.
@endcomponent
