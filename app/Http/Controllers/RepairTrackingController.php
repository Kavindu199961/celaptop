<?php

namespace App\Http\Controllers;

use App\Models\LaptopRepair;
use App\Models\CompletedRepair;
use Illuminate\Http\Request;

class RepairTrackingController extends Controller
{
    public function index(Request $request)
    {
        $repair = null;
        $steps = [];
        $status = null;

        if ($request->has('tracking_number')) {
            $customerNumber = $request->input('tracking_number');

            // Check in ongoing repairs first
            $repair = LaptopRepair::where('customer_number', $customerNumber)->first();
            
            if ($repair) {
                $status = 'ongoing';
                $steps = $this->getRepairSteps($repair->status);
            } else {
                // Check in completed repairs if not found in ongoing
                $repair = CompletedRepair::where('customer_number', $customerNumber)->first();
                
                if ($repair) {
                    $status = 'completed';
                    $steps = $this->getCompletedSteps();
                }
            }
        }

        return view('web.repair-tracking.index', compact('repair', 'steps', 'status'));
    }

    protected function getRepairSteps($currentStatus)
    {
        $allSteps = [
            'received' => ['label' => 'Device Received', 'description' => 'Your device has been received and logged into our system.'],
            'diagnosis' => ['label' => 'Diagnosis', 'description' => 'Our technicians are diagnosing the issue with your device.'],
            'parts_ordered' => ['label' => 'Parts Ordered', 'description' => 'Necessary parts have been ordered for your repair.'],
            'repairing' => ['label' => 'Repair In Progress', 'description' => 'Your device is currently being repaired.'],
            'testing' => ['label' => 'Quality Testing', 'description' => 'Repaired device is undergoing quality assurance tests.'],
            'ready' => ['label' => 'Ready for Pickup', 'description' => 'Your device is ready for pickup/delivery.'],
        ];

        $steps = [];
        $foundCurrent = false;

        foreach ($allSteps as $status => $step) {
            $steps[] = [
                'status' => $status,
                'label' => $step['label'],
                'description' => $step['description'],
                'active' => $status === $currentStatus,
                'completed' => !$foundCurrent && $status !== $currentStatus
            ];

            if ($status === $currentStatus) {
                $foundCurrent = true;
            }
        }

        return $steps;
    }

    protected function getCompletedSteps()
    {
        return [
            [
                'label' => 'Device Received',
                'description' => 'Your device was received at our service center.',
                'completed' => true
            ],
            [
                'label' => 'Diagnosis Completed',
                'description' => 'Our technicians diagnosed the issue with your device.',
                'completed' => true
            ],
            [
                'label' => 'Repair Completed',
                'description' => 'Your device was successfully repaired by our technicians.',
                'completed' => true
            ],
            [
                'label' => 'Quality Testing Passed',
                'description' => 'Device passed all quality assurance tests.',
                'completed' => true
            ],
            [
                'label' => 'Device Delivered',
                'description' => 'Your device was successfully delivered/collected.',
                'completed' => true
            ]
        ];
    }
}