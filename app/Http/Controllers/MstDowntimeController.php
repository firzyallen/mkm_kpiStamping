<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DowntimeMstMachine;
use App\Models\UnifiedShop;
use App\Models\UnifiedSection;
use Illuminate\Support\Facades\DB;

class MstDowntimeController extends Controller
{
    public function index()
    {
        $machines = DowntimeMstMachine::with('shop.section')->get();
        $shops = UnifiedShop::with('section')->get();
        $sections = UnifiedSection::all();

        return view('masterdowntime.machine', compact('machines', 'shops', 'sections'));
    }

    public function storeMachine(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'shop_id' => 'required|integer|exists:unified_shops,id',
            'machine' => 'required|array',
            'machine.*' => 'required|string|max:255',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            foreach ($request->machine as $machineName) {
                DowntimeMstMachine::create([
                    'shop_id' => $request->shop_id,
                    'machine_name' => $machineName,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('status', 'Machines created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create machines. Error: ' . $e->getMessage());
        }
    }

    public function updateMachine(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id' => 'required|integer|exists:downtime_mst_machines,id',
            'shop_id' => 'required|integer|exists:unified_shops,id',
            'machine_name' => 'required|string|max:255',
        ]);

        // Find the machine instance by its ID
        $machine = DowntimeMstMachine::findOrFail($request->id);

        // Start a database transaction
        DB::beginTransaction();

        try {
            $machine->update([
                'shop_id' => $request->shop_id,
                'machine_name' => $request->machine_name,
            ]);

            DB::commit();
            return redirect()->back()->with('status', 'Machine updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update machine. Error: ' . $e->getMessage());
        }
    }
}
