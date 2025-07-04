<?php

namespace App\Http\Controllers;

use App\Models\LaptopRepair;
use App\Models\CompletedRepair;
use App\Models\MyShopDetail;
use Illuminate\Http\Request;

class RepairTrackingController extends Controller
{
    public function index(Request $request)
    {
        $repair = null;
        $steps = [];
        $status = null;
        $shopDetails = null;

        if ($request->has('tracking_number')) {
            $trackingNumber = $request->input('tracking_number');
            $repair = $this->findRepair($trackingNumber);
            
            if ($repair) {
                $status = $repair instanceof CompletedRepair ? 'completed' : 'ongoing';
                $steps = $this->getRepairSteps($repair, $status);
                
                // Get shop details for the user who created this repair
                $shopDetails = MyShopDetail::where('user_id', $repair->user_id)->first();
            }
        }

        return view('web.repair-tracking.index', [
            'repair' => $repair,
            'steps' => $steps,
            'status' => $status,
            'request' => $request,
            'shopDetails' => $shopDetails
        ]);
    }

    protected function findRepair($trackingNumber)
    {
        // Check only by customer_number now (removed note_number)
        $repair = LaptopRepair::where('customer_number', $trackingNumber)->first();
        
        if (!$repair && class_exists(CompletedRepair::class)) {
            $repair = CompletedRepair::where('customer_number', $trackingNumber)->first();
        }
        
        return $repair;
    }

    protected function getRepairSteps($repair, $status)
    {
        if ($status === 'completed') {
            return $this->getCompletedSteps($repair);
        }
        
        return $this->getOngoingSteps($repair->status, $repair);
    }

    protected function getOngoingSteps($currentStatus, $repair)
    {
        $allSteps = [
            'received' => [
                'label' => 'Device Received', 
                'description' => 'Your device has been received and logged into our system.'
            ],
            'diagnosis' => [
                'label' => 'Diagnosis', 
                'description' => 'Our technicians are diagnosing the reported fault: ' . ($repair->fault ?? 'N/A')
            ],
            'parts_ordered' => [
                'label' => 'Parts Ordered', 
                'description' => 'Necessary parts have been ordered for your repair.'
            ],
            'repairing' => [
                'label' => 'Repair In Progress', 
                'description' => 'Your device is currently being repaired.'
            ],
            'testing' => [
                'label' => 'Quality Testing', 
                'description' => 'Repaired device is undergoing quality assurance tests.'
            ],
            'ready' => [
                'label' => 'Ready for Pickup', 
                'description' => 'Your device is ready for pickup. Repair price: ' . 
                                ($repair->repair_price ? 'Rs. ' . number_format($repair->repair_price, 2) : 'Pending')
            ],
        ];

        $steps = [];
        $foundCurrent = false;

        foreach ($allSteps as $statusKey => $step) {
            $steps[] = array_merge($step, [
                'status' => $statusKey,
                'active' => $statusKey === $currentStatus,
                'completed' => !$foundCurrent && $statusKey !== $currentStatus
            ]);

            if ($statusKey === $currentStatus) {
                $foundCurrent = true;
            }
        }

        return $steps;
    }

    protected function getCompletedSteps($repair)
    {
        return [
            [
                'label' => 'Device Received',
                'description' => 'Your device was received at our service center on ' . $repair->date->format('M j, Y'),
                'completed' => true,
                'active' => false
            ],
            [
                'label' => 'Diagnosis Completed',
                'description' => 'Our technicians diagnosed: ' . ($repair->fault ?? 'N/A'),
                'completed' => true,
                'active' => false
            ],
            [
                'label' => 'Repair Completed',
                'description' => 'Device was successfully repaired' . 
                                ($repair->repair_price ? ' for Rs. ' . number_format($repair->repair_price, 2) : ''),
                'completed' => true,
                'active' => false
            ],
            [
                'label' => 'Quality Testing Passed',
                'description' => 'Device passed all quality assurance tests',
                'completed' => true,
                'active' => false
            ],
            [
                'label' => 'Device Delivered',
                'description' => 'Device was successfully delivered/collected',
                'completed' => true,
                'active' => false
            ]
        ];
    }
}