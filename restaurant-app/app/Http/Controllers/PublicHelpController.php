<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Table;
use App\Models\User;
use App\Models\WaiterAssignment;
use App\Notifications\ServiceAlert;
use Illuminate\Http\Request;

class PublicHelpController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => ['required', 'exists:tables,id'],
        ]);

        $table = Table::findOrFail($request->integer('table_id'));

        $assignment = WaiterAssignment::where('table_id', $table->id)
            ->where('status', 'active')
            ->with('waiter')
            ->first();

        $incident = Incident::create([
            'table_id' => $table->id,
            'waiter_id' => $assignment?->waiter_id,
            'type' => 'needs_help',
            'description' => "Cliente solicita atención en mesa {$table->number}",
            'status' => 'open',
        ]);

        $table->update(['help_requested_at' => now()]);

        $jefes = User::role(['super_admin', 'admin'])->get();
        $alert = new ServiceAlert(
            table: $table,
            waiter: $assignment?->waiter ?? $jefes->first(),
            type: 'help_request',
            message: "Cliente solicita atención en mesa {$table->number}",
        );

        foreach ($jefes as $jefe) {
            $jefe->notify($alert);
        }

        if ($assignment?->waiter) {
            $assignment->waiter->notify($alert);
        }

        return response()->json(['success' => true, 'message' => 'Aviso enviado']);
    }
}
