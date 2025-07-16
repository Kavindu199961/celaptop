<?php

namespace App\Http\Controllers;

use App\Models\LaptopRepair;
use App\Models\CompletedRepair;
use App\Models\MyShopDetail;
use App\Models\RepairItem;
use Illuminate\Http\Request;

class RepairTrackingController extends Controller
{
    public function index(Request $request)
    {
        $repair = null;
        $repairItem = null;
        $steps = [];
        $status = null;
        $shopDetails = null;
        $searchType = null;

        if ($request->has('tracking_number')) {
            $trackingNumber = $request->input('tracking_number');

            $repair = $this->findRepair($trackingNumber);

            if ($repair) {
                $searchType = 'customer_number';
                $status = $repair instanceof CompletedRepair ? 'completed' : $repair->status;
                $steps = $this->getRepairSteps($repair, $status);
                $shopDetails = MyShopDetail::where('user_id', $repair->user_id)->first();
            } else {
                $repairItem = RepairItem::with('shop')->where('item_number', $trackingNumber)->first();

                if ($repairItem) {
                    $searchType = 'item_number';
                    $status = $repairItem->completedRepair ? 'completed' : $repairItem->status;
                    $steps = $this->getRepairItemSteps($repairItem, $status);
                    if ($repairItem->shop) {
                        $shopDetails = MyShopDetail::where('user_id', $repairItem->shop->user_id)->first();
                    }
                }
            }
        }

        return view('web.repair-tracking.index', [
            'repair' => $repair,
            'repairItem' => $repairItem,
            'steps' => $steps,
            'status' => $status,
            'request' => $request,
            'shopDetails' => $shopDetails,
            'searchType' => $searchType,
        ]);
    }

    protected function findRepair($trackingNumber)
    {
        $repair = LaptopRepair::where('customer_number', $trackingNumber)->first();
        if (!$repair && class_exists(CompletedRepair::class)) {
            $repair = CompletedRepair::where('customer_number', $trackingNumber)->first();
        }
        return $repair;
    }

    protected function getRepairSteps($repair, $status)
    {
        return $this->getDetailedSteps($status, $repair);
    }

    protected function getRepairItemSteps($repairItem, $status)
    {
        return $this->getDetailedItemSteps($status, $repairItem);
    }

    protected function getDetailedSteps($status, $repair)
    {
        $allSteps = [
            'pending' => [
                'label' => 'Pending',
                'description' => 'Your item has been received and is awaiting processing.'
            ],
            'received' => [
                'label' => 'Device Received',
                'description' => 'Your device has been received and logged into our system.'
            ],
            'diagnosis' => [
                'label' => 'Diagnosis',
                'description' => 'Technicians are diagnosing the device: ' . ($repair->fault ?? 'N/A')
            ],
            'repairing' => [
                'label' => 'Repairing',
                'description' => 'Repair is in progress.'
            ],
            'testing' => [
                'label' => 'Testing',
                'description' => 'Device is being tested after repair.'
            ],
            'ready' => [
                'label' => 'Ready for Pickup',
                'description' => 'Your device is ready for pickup.'
            ],
        ];

        return $this->markStepsByGroup($allSteps, $status);
    }

    protected function getDetailedItemSteps($status, $repairItem)
    {
        $allSteps = [
            'pending' => [
                'label' => 'Pending',
                'description' => 'Your item has been received and is awaiting processing.'
            ],
            'received' => [
                'label' => 'Item Received',
                'description' => 'Your item has been received and logged into our system with number: ' . $repairItem->item_number
            ],
            'diagnosis' => [
                'label' => 'Diagnosis',
                'description' => 'Our technicians are diagnosing the item: ' . ($repairItem->description ?? 'N/A')
            ],
            'repairing' => [
                'label' => 'Repair In Progress',
                'description' => 'Your item is currently being repaired.'
            ],
            'testing' => [
                'label' => 'Testing',
                'description' => 'Item is being tested after repair.'
            ],
            'ready' => [
                'label' => 'Ready for Pickup',
                'description' => $this->getReadyDescription($repairItem, $status)
            ],
        ];

        return $this->markStepsByGroup($allSteps, $status);
    }

    protected function getReadyDescription($repairItem, $currentStatus)
    {
        if ($currentStatus === 'completed') {
            $price = is_numeric($repairItem->final_price)
                ? 'Rs. ' . number_format((float) $repairItem->final_price, 2)
                : 'Pending';

            return 'Your item is ready for pickup. Repair Price: ' . $price;
        } else {
            return 'Your item is ready for pickup.';
        }
    }

    protected function markStepsByGroup($allSteps, $status)
{
    // Define groupings
    $activeStepsMap = [
        'pending' => ['pending', 'received'],
        'in_progress' => ['diagnosis', 'repairing'],
        'completed' => ['testing', 'ready']
    ];

    $steps = [];
    $activeGroup = $activeStepsMap[$status] ?? [];

    // Create a flat list of all keys (to track progress index)
    $stepKeys = array_keys($allSteps);
    $lastActiveIndex = max(array_map(fn($key) => array_search($key, $stepKeys), $activeGroup));

    foreach ($allSteps as $key => $step) {
        $stepIndex = array_search($key, $stepKeys);

        $isCompleted = $stepIndex < $lastActiveIndex;
        $isActive = in_array($key, $activeGroup);

        $steps[] = [
            'status' => $key,
            'label' => $step['label'],
            'description' => $step['description'],
            'completed' => $isCompleted,
            'active' => $isActive
        ];
    }

    return $steps;
}

}
