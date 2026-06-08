<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\SchedulingService;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct(private SchedulingService $scheduling) {}

    public function show(string $slug)
    {
        $tenant = Tenant::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $services = $tenant->services()->where('active', true)->get();

        return view('agenda', compact('tenant', 'services'));
    }

    public function slots(string $slug, Request $request)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date'       => 'required|date',
        ]);

        $service = $tenant->services()->findOrFail($request->service_id);

        $slots = $this->scheduling->getAvailableSlots(
            $tenant->id,
            $service->duration_minutes,
            $request->date
        );

        return response()->json($slots->map(fn ($s) => $s->format('H:i')));
    }

    public function store(string $slug, Request $request)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        $request->validate([
            'service_id'      => 'required|exists:services,id',
            'date'            => 'required|date',
            'time'            => 'required',
            'client_name'     => 'required|string',
            'client_whatsapp' => 'required|string',
        ]);

        $appointment = $this->scheduling->createAppointment([
            'tenant_id'       => $tenant->id,
            'service_id'      => $request->service_id,
            'client_name'     => $request->client_name,
            'client_whatsapp' => $request->client_whatsapp,
            'scheduled_at'    => $request->date . ' ' . $request->time,
        ]);

        return response()->json(['success' => true, 'id' => $appointment->id]);
    }
}