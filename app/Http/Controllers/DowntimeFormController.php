<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DowntimeFormHeader;
use App\Models\DowntimeFormDetail;
use App\Models\DowntimeFormActual;
use App\Models\UnifiedSection;
use App\Models\UnifiedShop;
use App\Models\DowntimeMstMachine;
use App\Models\Dropdown;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DowntimeDailyReportExport;

class DowntimeFormController extends Controller
{
    /**
     * Display a listing of downtime reports.
     */
    public function index()
    {
        $headers = DowntimeFormHeader::orderBy('date', 'desc')->get();
        $sectionTypes = UnifiedSection::all();
        $shifts = Dropdown::where('category', 'Shift')->pluck('name_value');

        return view('downtime-report.index', compact('headers', 'sectionTypes', 'shifts'));
    }

    /**
     * Store a newly created downtime report header in storage.
     */
    public function storeHeader(Request $request)
    {
        $request->validate([
            'section_id' => 'required|integer|exists:unified_sections,id',
            'shift' => 'required|string|max:50',
            'date' => 'required|date',
        ]);

        $existingHeader = DowntimeFormHeader::where('section_id', $request->section_id)
            ->where('shift', $request->shift)
            ->where('date', $request->date)
            ->first();

        if ($existingHeader) {
            return redirect()->back()->withInput()->withErrors(['error' => 'A downtime report for this section, shift, and date already exists.']);
        }

        $header = new DowntimeFormHeader();
        $header->section_id = $request->section_id;
        $header->shift = $request->shift;
        $header->date = $request->date;
        $header->created_by = auth()->user()->name;
        $header->save();

        $encryptedId = encrypt($header->id);
        return redirect()->route('downtime.form', ['id' => $encryptedId])->with('status', 'Downtime header created successfully.');
    }

    /**
     * Show the form for creating a new downtime report.
     */
    public function formDowntime($id)
    {
        $id = decrypt($id);
        $header = DowntimeFormHeader::with(['details.downtimeActuals'])->findOrFail($id);
        $machines = DowntimeMstMachine::all();
        $shops = UnifiedShop::all();

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
            $headerId = $request->header_id;

            foreach ($request->shop as $index => $shop) {
                // Handle photo upload
                $imgPath = null;
                if ($request->hasFile("photo.$index")) {
                    $file = $request->file("photo.$index");
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/img/downtime_photos/');
                    $file->move($destinationPath, $fileName);
                    $imgPath = 'assets/img/downtime_photos/' . $fileName;
                }

                // Create details record
                $details = DowntimeFormDetail::create([
                    'header_id' => $headerId,
                    'shop_id' => $shop,
                    'reporter' => $request->reporter[$index],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $detailId = $details->id;

                // Create actual record
                DowntimeFormActual::create([
                    'details_id' => $detailId,
                    'machine_id' => $request->machine[$index],
                    'photo' => $imgPath ?? null,
                    'category' => $request->downtime_cause[$index],
                    'shop_call' => $request->shop_call[$index] ?? null,
                    'problem' => $request->problem[$index],
                    'cause' => $request->cause[$index] ?? null,
                    'action' => $request->action[$index] ?? null,
                    'judgement' => $request->judgement[$index] ?? null,
                    'start_time' => $request->start_time[$index],
                    'end_time' => $request->end_time[$index] ?? null,
                    'balance' => $request->balance[$index] ?? null,
                    'percentage' => $request->percentage[$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
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
        $header = DowntimeFormHeader::findOrFail($id);
        $details = DowntimeFormDetail::where('header_id', $id)->get();
        $actuals = DowntimeFormActual::whereIn('details_id', $details->pluck('id'))->get();

        $formattedData = [];
        foreach ($details as $detail) {
            $shop = $detail->shop;
            $actualDetails = $actuals->where('details_id', $detail->id);

            $shopData = [
                'shop_name' => $shop->shop_name,
                'reporter' => $detail->reporter,
                'actuals' => [],
            ];

            foreach ($actualDetails as $actual) {
                $shopData['actuals'][] = [
                    'machine_name' => $actual->machine->machine_name,
                    'category' => $actual->category,
                    'shop_call' => $actual->shop_call,
                    'problem' => $actual->problem,
                    'cause' => $actual->cause,
                    'action' => $actual->action,
                    'judgement' => $actual->judgement,
                    'start_time' => $actual->start_time,
                    'end_time' => $actual->end_time,
                    'balance' => $actual->balance,
                    'percentage' => $actual->percentage,
                    'photo' => $actual->photo,
                ];
            }

            $formattedData[] = $shopData;
        }

        return view('downtime-report.show', compact('header', 'formattedData', 'id'));
    }

    /**
     * Show the form for editing the specified downtime report.
     */
    public function updateDetail($id)
    {

        $id = decrypt($id);
        $header = DowntimeFormHeader::with(['details.downtimeActuals.machine', 'details.shop'])->findOrFail($id);
        $machines = DowntimeMstMachine::all();
        $shops = UnifiedShop::all();

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

        $formattedData = [];
        foreach ($header->details as $detail) {
            $shop = $detail->shop;
            $actualDetails = $detail->downtimeActuals;

            $shopData = [
                'shop_id' => $shop->id,
                'reporter' => $detail->reporter,
                'actuals' => [],
            ];

            foreach ($actualDetails as $actual) {
                $shopData['actuals'][] = [
                    'machine_id' => $actual->machine->id,
                    'category' => $actual->category,
                    'shop_call' => $actual->shop_call,
                    'problem' => $actual->problem,
                    'cause' => $actual->cause,
                    'action' => $actual->action,
                    'judgement' => $actual->judgement,
                    'start_time' => $actual->start_time,
                    'end_time' => $actual->end_time,
                    'balance' => $actual->balance,
                    'percentage' => $actual->percentage,
                    'photo' => $actual->photo,
                ];
            }

            $formattedData[] = $shopData;
        }

        return view('downtime-report.update', compact('header', 'machines', 'shops', 'downtimeCategories', 'judgements', 'formattedData', 'id'));
    }

    /**
     * Update the specified downtime report in storage.
     */
    public function updateForm(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $headerId = decrypt($id);

            // Fetch existing details and actuals IDs from the database
            $existingDetailIds = DowntimeFormDetail::where('header_id', $headerId)->pluck('id')->toArray();
            $existingActualIds = DowntimeFormActual::whereIn('details_id', $existingDetailIds)->pluck('id')->toArray();

            $submittedDetailIds = [];
            $submittedActualIds = [];

            foreach ($request->shop as $index => $shop) {
                // Update or create details record
                $details = DowntimeFormDetail::updateOrCreate(
                    [
                        'header_id' => $headerId,
                        'shop_id' => $shop,
                    ],
                    [
                        'reporter' => $request->reporter[$index],
                        'updated_at' => now(),
                    ]
                );

                $detailId = $details->id;
                $submittedDetailIds[] = $detailId;

                // Handle photo upload
                $imgPath = null;
                if ($request->hasFile("photo.$index")) {
                    $file = $request->file("photo.$index");
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/img/downtime_photos/');
                    $file->move($destinationPath, $fileName);
                    $imgPath = 'assets/img/downtime_photos/' . $fileName;
                } else {
                    // Check if an actual entry already exists
                    $existingActual = DowntimeFormActual::where([
                        ['details_id', '=', $detailId],
                        ['machine_id', '=', $request->machine[$index]],
                        ['category', '=', $request->downtime_cause[$index]],
                    ])->first();

                    if ($existingActual) {
                        $imgPath = $existingActual->photo;
                    }
                }

                // Update or create actual record
                $actual = DowntimeFormActual::updateOrCreate(
                    [
                        'details_id' => $detailId,
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
                        'photo' => $imgPath,
                        'updated_at' => now(),
                    ]
                );

                $submittedActualIds[] = $actual->id;
            }

            // Delete details and actuals that were removed
            DowntimeFormActual::whereIn('id', array_diff($existingActualIds, $submittedActualIds))->delete();
            DowntimeFormDetail::whereIn('id', array_diff($existingDetailIds, $submittedDetailIds))->delete();

            DB::commit();
            return redirect('/downtime-report')->with('status', 'Downtime report updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/downtime-report')->with('failed', 'Failed to update downtime report. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified downtime report header from storage.
     */
    public function destroy($id)
    {
        $id = decrypt($id);
        $header = DowntimeFormHeader::findOrFail($id);

        // Delete related details and actuals
        foreach ($header->details as $detail) {
            $detail->downtimeActuals()->delete();
            $detail->delete();
        }

        $header->delete();

        return redirect('/downtime-report')->with('status', 'Downtime report deleted successfully.');
    }

    public function exportExcel(Request $request)
    {
        $month = $request->input('month');
        return Excel::download(new DowntimeDailyReportExport($month), "downtime_daily_report_export.xlsx");
    }
}
