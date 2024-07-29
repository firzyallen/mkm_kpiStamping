<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DowntimeFormHeader;
use App\Models\DowntimeFormDetails;
use App\Models\DowntimeFormActual;
use App\Models\UnifiedShop;
use App\Models\DowntimeMstMachine;
use App\Models\Dropdown;
use Illuminate\Support\Facades\DB;

class DowntimeFormController extends Controller
{
    /**
     * Display a listing of downtime reports.
     */
    public function index()
    {
        $headers = DowntimeFormHeader::with('details')->get();
        $shopTypes = UnifiedShop::distinct()->pluck('shop_type');
        $shifts = Dropdown::where('category', 'Shift')->pluck('name_value');

        return view('downtime-report.index', compact('headers', 'shopTypes', 'shifts'));
    }

    /**
     * Store a newly created downtime report header in storage.
     */
    public function storeHeader(Request $request)
    {
        $request->validate([
            'shop_type' => 'required|string|max:50',
            'shift' => 'required|string|max:50',
            'date' => 'required|date',
        ]);

        $exists = DowntimeFormHeader::where('shop_type', $request->shop_type)
            ->where('shift', $request->shift)
            ->where('date', $request->date)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'A downtime report for this section, shift, and date already exists.');
        }

        $header = DowntimeFormHeader::create([
            'shop_type' => $request->shop_type,
            'shift' => $request->shift,
            'date' => $request->date,
            'created_by' => auth()->user()->name,
        ]);

        return redirect('/downtime-report/form/' . encrypt($header->id))->with('status', 'Downtime header created successfully.');
    }

    /**
     * Show the form for creating a new downtime report.
     */
    public function formDowntime($id)
    {
        $id = decrypt($id);
        $header = DowntimeFormHeader::with('details.actuals')->findOrFail($id);
        $machines = DowntimeMstMachine::all();
        $shops = UnifiedShop::all();

        // Hardcoded downtime categories and judgements
        $downtimeCategories = [
            'Machine & PLN Off' => 'ME/MTC',
            'Tooling' => 'ME/MTC',
            'Quality Material' => 'QM',
            'Manpower, Repair, IDLE (Downtime Process)' => 'OP'
        ];

        $judgements = [
            'OK' => 'OK',
            'Not Good' => 'NG',
            'Temporary' => 'Temp'
        ];

        return view('downtime-report.form', compact('header', 'machines', 'shops', 'downtimeCategories', 'judgements'));
    }

    /**
     * Store the newly created downtime report details and actuals in storage.
     */
    public function storeForm(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'header_id' => 'required|integer|exists:downtime_form_headers,id',
                'shop' => 'required|array',
                'machine' => 'required|array',
                'downtime_cause' => 'required|array',
                'shop_call' => 'required|array',
                'problem' => 'nullable|array',
                'cause' => 'nullable|array',
                'action' => 'nullable|array',
                'judgement' => 'nullable|array',
                'start_time' => 'required|array',
                'end_time' => 'nullable|array',
                'balance' => 'nullable|array',
                'percentage' => 'nullable|array',
            ]);

            foreach ($request->shop as $index => $shop) {
                // Create a new details record
                $details = DowntimeFormDetails::create([
                    'header_id' => $request->header_id,
                    'shop_id' => $shop,
                    'reporter' => auth()->user()->name,
                ]);

                // Insert data into downtime_form_actual
                DowntimeFormActual::create([
                    'details_id' => $details->id,
                    'machine_id' => $request->machine[$index],
                    'category' => $request->downtime_cause[$index],
                    'shop_call' => $request->shop_call[$index],
                    'problem' => $request->problem[$index] ?? null,
                    'cause' => $request->cause[$index] ?? null,
                    'action' => $request->action[$index] ?? null,
                    'judgement' => $request->judgement[$index] ?? null,
                    'start_time' => $request->start_time[$index],
                    'end_time' => $request->end_time[$index] ?? null,
                    'balance' => $request->balance[$index] ?? null,
                    'percentage' => $request->percentage[$index] ?? null,
                ]);
            }

            DB::commit();
            return redirect('/downtime-report')->with('status', 'Downtime report saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/downtime-report')->with('failed', 'Failed to save downtime report. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified downtime report.
     */
    public function showDetail($id)
    {
        $id = decrypt($id);
        $header = DowntimeFormHeader::with('details.actuals')->findOrFail($id);
        return view('downtime-report.show', compact('header'));
    }

    /**
     * Show the form for editing the specified downtime report.
     */
    public function updateDetail($id)
    {
        $id = decrypt($id);

        // Fetch downtime header data with relations
        $header = DowntimeFormHeader::with(['details.actuals.machine', 'details.shop'])->findOrFail($id);

        // Fetch all necessary data
        $machines = DowntimeMstMachine::all();
        $shops = UnifiedShop::all();

        // Remove the database fetch for dropdowns
        $downtimeCategories = [
            'Machine & PLN Off' => 'ME/MTC',
            'Tooling' => 'ME/MTC',
            'Quality Material' => 'QM',
            'Manpower, Repair, IDLE (Downtime Process)' => 'OP'
        ];

        $judgements = [
            'OK' => 'OK',
            'Not Good' => 'NG',
            'Temporary' => 'Temp'
        ];
        dd($id);

        return view('downtime-report.update', compact('header', 'machines', 'shops', 'downtimeCategories', 'judgements'));
    }

    /**
     * Update the specified downtime report in storage.
     */
    public function updateForm(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $headerId = decrypt($id);

            $request->validate([
                'shop' => 'required|array',
                'machine' => 'required|array',
                'downtime_cause' => 'required|array',
                'shop_call' => 'required|array',
                'problem' => 'nullable|array',
                'cause' => 'nullable|array',
                'action' => 'nullable|array',
                'judgement' => 'nullable|array',
                'start_time' => 'required|array',
                'end_time' => 'nullable|array',
                'balance' => 'nullable|array',
                'percentage' => 'nullable|array',
            ]);

            foreach ($request->shop as $index => $shop) {
                $details = DowntimeFormDetails::where('header_id', $headerId)
                    ->where('shop_id', $shop)
                    ->firstOrCreate([
                        'header_id' => $headerId,
                        'shop_id' => $shop,
                    ], [
                        'reporter' => auth()->user()->name,
                    ]);

                DowntimeFormActual::updateOrCreate(
                    [
                        'details_id' => $details->id,
                        'machine_id' => $request->machine[$index],
                        'category' => $request->downtime_cause[$index],
                    ],
                    [
                        'shop_call' => $request->shop_call[$index],
                        'problem' => $request->problem[$index] ?? null,
                        'cause' => $request->cause[$index] ?? null,
                        'action' => $request->action[$index] ?? null,
                        'judgement' => $request->judgement[$index] ?? null,
                        'start_time' => $request->start_time[$index],
                        'end_time' => $request->end_time[$index] ?? null,
                        'balance' => $request->balance[$index] ?? null,
                        'percentage' => $request->percentage[$index] ?? null,
                    ]
                );
            }

            DB::commit();
            return redirect('/downtime-report')->with('status', 'Downtime report updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/downtime-report')->with('failed', 'Failed to update downtime report. Please try again. Error: ' . $e->getMessage());
        }
    }
}
