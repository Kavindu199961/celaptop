<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailySummary;
use App\Models\Appointment;

class GenerateDailySummary extends Command
{
    protected $signature = 'summary:generate';
    protected $description = 'Generate daily summary of appointments';

    public function handle()
    {
        $today = now()->format('Y-m-d');
        
        // Check if summary already exists for today
        if (DailySummary::where('summary_date', $today)->exists()) {
            $this->info('Daily summary already exists for today.');
            return;
        }

        // Get all appointments for today grouped by doctor
        $appointments = Appointment::with('doctor')
            ->whereDate('created_at', today())
            ->selectRaw('doctor_id, COUNT(*) as appointment_count, SUM(doctor_fee) as total_doctor_fee')
            ->groupBy('doctor_id')
            ->orderBy('appointment_count', 'desc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'doctor_id' => $appointment->doctor_id,
                    'doctor_name' => $appointment->doctor->name ?? 'Unknown Doctor',
                    'doctor_position' => $appointment->doctor->position ?? 'Not Specified',
                    'appointment_count' => $appointment->appointment_count,
                    'total_doctor_fee' => $appointment->total_doctor_fee,
                    'average_fee' => $appointment->appointment_count > 0 
                        ? $appointment->total_doctor_fee / $appointment->appointment_count 
                        : 0
                ];
            });

        // Create the daily summary
        DailySummary::create([
            'summary_date' => $today,
            'total_appointments' => $appointments->sum('appointment_count'),
            'total_fees' => $appointments->sum('total_doctor_fee'),
            'doctor_details' => $appointments->toArray()
        ]);

        $this->info('Daily summary generated successfully for ' . $today);
    }
}