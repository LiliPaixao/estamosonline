<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SchedulingService
{
    public function getAvailableSlots(int $tenantId, int $durationMinutes, string $date): Collection
    {
        $carbon = Carbon::parse($date);
        $weekday = $carbon->dayOfWeek;

        // Busca os horários de trabalho do dia
        $availability = Availability::where('tenant_id', $tenantId)
            ->where('weekday', $weekday)
            ->first();

        if (!$availability) {
            return collect();
        }

        // Gera todos os slots do dia baseado no intervalo
        $slots = $this->generateSlots(
            $carbon->copy()->setTimeFromTimeString($availability->start_time),
            $carbon->copy()->setTimeFromTimeString($availability->end_time),
            $availability->slot_interval_minutes,
            $durationMinutes
        );

        // Busca agendamentos já existentes no dia
        $appointments = Appointment::where('tenant_id', $tenantId)
            ->whereDate('scheduled_at', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        // Filtra slots que colidem com agendamentos existentes
        return $slots->filter(function (Carbon $slot) use ($appointments, $durationMinutes) {
            $slotEnd = $slot->copy()->addMinutes($durationMinutes);

            foreach ($appointments as $appointment) {
                $apptStart = Carbon::parse($appointment->scheduled_at);
                $apptEnd   = Carbon::parse($appointment->ends_at);

                // Verifica sobreposição
                $overlaps = $slot->lt($apptEnd) && $slotEnd->gt($apptStart);

                if ($overlaps) {
                    return false;
                }
            }

            return true;
        })->values();
    }

    private function generateSlots(
        Carbon $start,
        Carbon $end,
        int $intervalMinutes,
        int $durationMinutes
    ): Collection {
        $slots = collect();
        $current = $start->copy();

        // O último slot possível é end - duration
        // ex: fim 18:00, duração 2h → último slot é 16:00
        $lastPossible = $end->copy()->subMinutes($durationMinutes);

        while ($current->lte($lastPossible)) {
            $slots->push($current->copy());
            $current->addMinutes($intervalMinutes);
        }

        return $slots;
    }

    public function createAppointment(array $data): Appointment
    {
        $service = \App\Models\Service::findOrFail($data['service_id']);

        $scheduledAt = Carbon::parse($data['scheduled_at']);
        $endsAt      = $scheduledAt->copy()->addMinutes($service->duration_minutes);

        return Appointment::create([
            'tenant_id'        => $data['tenant_id'],
            'service_id'       => $data['service_id'],
            'client_name'      => $data['client_name'],
            'client_whatsapp'  => $data['client_whatsapp'],
            'scheduled_at'     => $scheduledAt,
            'ends_at'          => $endsAt,
            'status'           => 'pending',
        ]);
    }
}