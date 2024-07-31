<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\PressActualHeader;
use App\Models\PressActualFormDetail;
use App\Models\PressActualFormProduction;
use App\Models\PressActualFormNg;
use App\Models\PressMstModel;
use App\Models\PressMstShop;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PressDailyReportExport;


class FormPressController extends Controller
{
    /**
     * Display a listing of press daily reports.
     */
    public function index()
    {
        $items = PressActualHeader::all();
        $categories = DB::table('dropdowns')->where('category', 'Shift')->get();

        return view('daily-report.press.index', compact('items', 'categories'));
    }

    /**
     * Store a newly created press daily report header in storage.
     */
    public function storeMain(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'shift' => 'required|string|max:255',
            'date' => 'required|date',
            'pic' => 'required|string|max:255',
        ]);

        // Check for existing data with the same date and shift
        $existingHeader = PressActualHeader::where('date', $request->date)
            ->where('shift', $request->shift)
            ->first();

        if ($existingHeader) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Daily Report for this date and shift already exists.']);
        }

        // Create a new instance of the PressActualHeader model
        $header = new PressActualHeader();
        $header->date = $request->date;
        $header->shift = $request->shift;
        $header->revision = 0;
        $header->created_by = auth()->user()->name;
        $header->pic = $request->pic;

        // Save the new PressActualHeader record to the database
        $header->save();

        // Redirect back or return a response as needed
        $encryptedId = encrypt($header->id);
        return redirect()->route('form.daily-report.press', ['id' => $encryptedId])->with('status', 'Daily Report header created successfully.');
    }

    /**
     * Show the form for creating a new press daily report.
     */
    public function formPress($id)
    {
        $id = decrypt($id);
        $item = PressActualHeader::findOrFail($id);
        $shops = PressMstShop::all();
        $models = PressMstModel::all();

        // Determine the working hours based on the day of the week and shift
        $dayOfWeek = \Carbon\Carbon::parse($item->date)->dayOfWeek; // 0 (for Sunday) through 6 (for Saturday)
        $workingHour = 0;

        if ($item->shift == 'Day') {
            if ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
                // Monday to Thursday
                $workingHour = 7.58;
            } elseif ($dayOfWeek == 5) {
                // Friday
                $workingHour = 7.00;
            }
        } elseif ($item->shift == 'Night') {
            $workingHour = 6.75;
        }

        $formattedData = [];
        foreach ($models as $model) {
            $shop = $shops->where('id', $model->shop_id)->first();
            $formattedData[] = [
                'shop_name' => $shop->shop_name,
                'model_name' => $model->model_name,
            ];
        }


        return view('daily-report.press.form', compact('formattedData', 'item', 'id', 'workingHour'));
    }


    /**
     * Store the newly created press daily report details and actuals in storage.
     */
    public function storeForm(Request $request)
    {
        DB::beginTransaction();

        try {
            $headerId = $request->header_id;
            $shift = $request->shift; // Assuming shift is passed in the request

            foreach ($request->shop as $shop) {
                // Debugging: Log request data for each shop
                Log::info("Processing shop: $shop");
                Log::info("Manpower: " . print_r($request->manpower[$shop], true));
                Log::info("Production data: " . print_r($request->production[$shop], true));

                $imgPath = null;
                if ($request->hasFile("photo_shop.$shop.0")) {
                    $file = $request->file("photo_shop.$shop.0");
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/img/photo_shop/press/shop/');
                    $file->move($destinationPath, $fileName);
                    $imgPath = 'assets/img/photo_shop/press/shop/' . $fileName;
                }
                $shopId = PressMstShop::where('shop_name', $shop)->value('id');

                $detail = PressActualFormDetail::create([
                    'header_id' => $headerId,
                    'shop_id' => $shopId,
                    'manpower' => $request->manpower[$shop][0],
                    'manpower_plan' => $request->manpower_plan[$shop][0],
                    'working_hour' => $request->working_hour[$shop][0],
                    'notes' => $request->notes[$shop][0] ?? null,
                    'photo_shop' => $imgPath,
                ]);

                $detailId = $detail->id;

                foreach ($request->production[$shop]['model'] as $index => $model) {
                    // Ensure each production item has the required keys
                    if (!isset($model)) {
                        Log::error("Model key is missing for production at index $index for shop $shop");
                        Log::error("Production data: " . print_r($request->production[$shop], true));
                        throw new \Exception("Model key is missing for production at index $index for shop $shop");
                    }

                    $imgPathNG = null;
                    if ($request->hasFile("ng.$shop.photo_ng.$index")) {
                        $file = $request->file("ng.$shop.photo_ng.$index");
                        $fileName = uniqid() . '_' . $file->getClientOriginalName();
                        $destinationPath = public_path('assets/img/photo_shop/press/ng/');
                        $file->move($destinationPath, $fileName);
                        $imgPathNG = 'assets/img/photo_shop/press/ng/' . $fileName;
                    }
                    $modelId = PressMstModel::where('model_name', $model)->value('id');

                    $productionId = PressActualFormProduction::create([
                        'details_id' => $detailId,
                        'model_id' => $modelId,
                        'prod_process' => $request->production[$shop]['production_process'][$index],
                        'status' => $request->production[$shop]['status'][$index],
                        'type' => $request->production[$shop]['type'][$index],
                        'inc_material' => $request->production[$shop]['inc_material'][$index] ?? null,
                        'machine' => $request->production[$shop]['machine'][$index] ?? 0,
                        'setting' => $request->production[$shop]['setting'][$index] ?? 0,
                        'hour_from' => $request->production[$shop]['hour_from'][$index] ?? null,
                        'hour_to' => $request->production[$shop]['hour_to'][$index] ?? null,
                        'plan_prod' => $request->production[$shop]['plan_prod'][$index] ?? 0,
                        'OK' => $request->production[$shop]['OK'][$index] ?? 0,
                        'manpower' => $request->production[$shop]['manpower'][$index] ?? 0,
                    ])->id;

                    PressActualFormNg::create([
                        'production_id' => $productionId,
                        'model_id' => $modelId,
                        'OK' => $request->production[$shop]['OK'][$index] ?? 0,
                        'rework' => $request->ng[$shop]['rework'][$index] ?? 0,
                        'dmg_part' => $request->ng[$shop]['dmg_part'][$index] ?? 0,
                        'dmg_rm' => $request->ng[$shop]['dmg_rm'][$index] ?? 0,
                        'remarks' => $request->ng[$shop]['remarks'][$index] ?? null,
                        'photo_ng' => $imgPathNG,
                    ]);
                }
            }

            DB::commit();
            return redirect('/daily-report/press')->with('status', 'Daily report data saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving daily report data: ' . $e->getMessage());
            return redirect('/daily-report/press')->with('failed', 'Failed to save daily report data. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified press daily report.
     */
    public function showDetail($id)
    {
        $id = decrypt($id);

        $header = PressActualHeader::findOrFail($id);
        $details = PressActualFormDetail::where('header_id', $id)->get();
        $productions = PressActualFormProduction::whereIn('details_id', $details->pluck('id'))->get();
        $notGoods = PressActualFormNg::whereIn('production_id', $productions->pluck('id'))->get();

        $formattedData = [];
        foreach ($details as $detail) {
            $shop = PressMstShop::find($detail->shop_id);
            $models = $productions->where('details_id', $detail->id);

            $shopData = [
                'shop_name' => $shop->shop_name,
                'manpower' => $detail->manpower,
                'manpower_plan' => $detail->manpower_plan,
                'working_hour' => $detail->working_hour,
                'notes' => $detail->notes,
                'photo_shop' => $detail->photo_shop,
                'models' => [],
            ];

            foreach ($models as $model) {
                $shopData['models'][] = [
                    'model_id' => $model->model_id,
                    'model_name' => PressMstModel::where('id', $model->model_id)->value('model_name'),
                    'production_process' => $model->prod_process,
                    'status' => $model->status,
                    'type' => $model->type,
                    'inc_material' => $model->inc_material,
                    'machine' => $model->machine,
                    'manpower' => $model->manpower,
                    'setting' => $model->setting,
                    'hour_from' => Carbon::parse($model->hour_from)->format('H:i'),
                    'hour_to' => Carbon::parse($model->hour_to)->format('H:i'),
                    'plan_prod' => $model->plan_prod,
                    'OK' => $model->OK,
                    'rework' => $notGoods->where('production_id', $model->id)->first()->rework ?? null,
                    'dmg_part' => $notGoods->where('production_id', $model->id)->first()->dmg_part ?? null,
                    'dmg_rm' => $notGoods->where('production_id', $model->id)->first()->dmg_rm ?? null,
                    'remarks' => $notGoods->where('production_id', $model->id)->first()->remarks ?? null,
                    'photo_ng' => $notGoods->where('production_id', $model->id)->first()->photo_ng ?? null,
                ];
            }

            $formattedData[] = $shopData;
        }

        return view('daily-report.press.show', compact('header', 'formattedData', 'id'));
    }

    /**
     * Show the form for editing the specified press daily report.
     */
    public function updateDetail($id)
    {
        $id = decrypt($id);

        $header = PressActualHeader::findOrFail($id);
        $details = PressActualFormDetail::where('header_id', $id)->get();
        $productions = PressActualFormProduction::whereIn('details_id', $details->pluck('id'))->get();
        $notGoods = PressActualFormNg::whereIn('production_id', $productions->pluck('id'))->get();

        $formattedData = [];
        foreach ($details as $detail) {
            $shop = PressMstShop::find($detail->shop_id);
            $models = $productions->where('details_id', $detail->id);

            $shopData = [
                'shop_name' => $shop->shop_name,
                'manpower' => $detail->manpower,
                'manpower_plan' => $detail->manpower_plan,
                'working_hour' => $detail->working_hour,
                'notes' => $detail->notes,
                'photo_shop' => $detail->photo_shop,
                'models' => [],
            ];

            foreach ($models as $model) {
                $shopData['models'][] = [
                    'production_id' => $model->id,
                    'model_id' => $model->model_id,
                    'model_name' => PressMstModel::where('id', $model->model_id)->value('model_name'),
                    'production_process' => $model->prod_process,
                    'status' => $model->status,
                    'type' => $model->type,
                    'inc_material' => $model->inc_material,
                    'machine' => $model->machine,
                    'manpower' => $model->manpower,
                    'setting' => $model->setting,
                    'hour_from' => Carbon::parse($model->hour_from)->format('H:i'),
                    'hour_to' => Carbon::parse($model->hour_to)->format('H:i'),
                    'plan_prod' => $model->plan_prod,
                    'OK' => $model->OK,
                    'rework' => $notGoods->where('production_id', $model->id)->first()->rework ?? null,
                    'dmg_part' => $notGoods->where('production_id', $model->id)->first()->dmg_part ?? null,
                    'dmg_rm' => $notGoods->where('production_id', $model->id)->first()->dmg_rm ?? null,
                    'remarks' => $notGoods->where('production_id', $model->id)->first()->remarks ?? null,
                    'photo_ng' => $notGoods->where('production_id', $model->id)->first()->photo_ng ?? null,
                ];
            }

            $formattedData[] = $shopData;
        }
        return view('daily-report.press.update', compact('header', 'formattedData', 'id'));
    }

    /**
     * Update the specified press daily report in storage.
     */
    public function updateForm(Request $request)
    {
        DB::beginTransaction();

        try {
            $headerId = $request->id;

            foreach ($request->shop as $shop) {
                $imgPath = null;
                if ($request->hasFile("photo_shop.$shop.0")) {
                    $file = $request->file("photo_shop.$shop.0");
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/img/photo_shop/press/shop/');
                    $file->move($destinationPath, $fileName);
                    $imgPath = 'assets/img/photo_shop/press/shop/' . $fileName;
                }
                $shopId = PressMstShop::where('shop_name', $shop)->value('id');

                $detail = PressActualFormDetail::where('header_id', $headerId)->where('shop_id', $shopId)->first();

                if ($detail) {
                    $detail->update([
                        'manpower' => $request->manpower[$shop][0] ?? $detail->manpower,
                        'manpower_plan' => $request->manpower_plan[$shop][0] ?? $detail->manpower_plan,
                        'working_hour' => $request->working_hour[$shop][0] ?? $detail->working_hour,
                        'notes' => $request->notes[$shop][0] ?? $detail->notes,
                        'photo_shop' => $imgPath ?? $detail->photo_shop,
                    ]);
                }
            }
            foreach ($request->production as $prodId => $productionData) {
                $modelId = PressActualFormProduction::where('id', $prodId)->value('model_id');
                $modelShopId = PressMstModel::where('id', $modelId)->value('shop_id');
                $productionRecord = PressActualFormProduction::where('id', $prodId)->first();
                if ($productionRecord) {
                    $productionRecord->update([
                        'prod_process' => $productionData['production_process'][0] ?? $productionRecord->prod_process,
                        'status' => $productionData['status'][0] ?? $productionRecord->status,
                        'type' => $productionData['type'][0] ?? $productionRecord->type,
                        'inc_material' => $productionData['inc_material'][0] ?? $productionRecord->inc_material,
                        'machine' => $productionData['machine'][0] ?? $productionRecord->machine,
                        'setting' => $productionData['setting'][0] ?? $productionRecord->setting,
                        'hour_from' => $productionData['hour_from'][0] ?? $productionRecord->hour_from,
                        'hour_to' => $productionData['hour_to'][0] ?? $productionRecord->hour_to,
                        'plan_prod' => $productionData['plan_prod'][0] ?? $productionRecord->plan_prod,
                        'OK' => $productionData['OK'][0] ?? $productionRecord->OK,
                        'manpower' => $productionData['manpower'][0] ?? $productionRecord->manpower,
                    ]);
                } else {
                    $productionRecord = PressActualFormProduction::create([
                        'details_id' => $detail->id,
                        'model_id' => $modelId,
                        'prod_process' => $productionData['production_process'][0],
                        'status' => $productionData['status'][0],
                        'type' => $productionData['type'][0],
                        'inc_material' => $productionData['inc_material'][0] ?? null,
                        'machine' => $productionData['machine'][0],
                        'setting' => $productionData['setting'][0] ?? null,
                        'hour_from' => $productionData['hour_from'][0] ?? null,
                        'hour_to' => $productionData['hour_to'][0] ?? null,
                        'plan_prod' => $productionData['plan_prod'][0] ?? 0,
                        'OK' => $productionData['OK'][0] ?? 0,
                        'manpower' => $productionData['manpower'][0] ?? 0,
                    ]);
                }
                $imgPathNG = null;
                if ($request->hasFile("ng.$prodId.photo_ng.0")) {
                    $file = $request->file("ng.$prodId.photo_ng.0");
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/img/photo_shop/press/ng/');
                    $file->move($destinationPath, $fileName);
                    $imgPathNG = 'assets/img/photo_shop/press/ng/' . $fileName;
                }
                $ng = $request->ng[$prodId];
                $ngRecord = PressActualFormNg::where('production_id', $prodId)->first();

                if ($ngRecord) {
                    $ngRecord->update([
                        'OK' => $ng['OK'][0] ?? $ngRecord->OK,
                        'rework' => $ng['rework'][0] ?? $ngRecord->rework,
                        'dmg_part' => $ng['dmg_part'][0] ?? $ngRecord->dmg_part,
                        'dmg_rm' => $ng['dmg_rm'][0] ?? $ngRecord->dmg_rm,
                        'remarks' => $ng['remarks'][0] ?? $ngRecord->remarks,
                        'photo_ng' => $imgPathNG ?? $ngRecord->photo_ng,
                    ]);
                } else {
                    PressActualFormNg::create([
                        'production_id' => $prodId,
                        'model_id' => $modelId,
                        'OK' => $ng['OK'][0] ?? 0,
                        'rework' => $ng['rework'][0] ?? 0,
                        'dmg_part' => $ng['dmg_part'][0] ?? 0,
                        'dmg_rm' => $ng['dmg_rm'][0] ?? 0,
                        'remarks' => $ng['remarks'][0] ?? null,
                        'photo_ng' => $imgPathNG,
                    ]);
                }
            }
            DB::commit();
            return redirect('/daily-report/press')->with('status', 'Daily report data updated successfully.');
         }
        catch (\Exception $e) {
        DB::rollBack();
        return redirect('/daily-report/press')->with('failed', 'Failed to update daily report data. Please try again. Error: ' . $e->getMessage());
    }
    }

    public function destroy($id)
{
    DB::beginTransaction();

    try {
        // Decrypt the header ID
        $headerId = decrypt($id);

        // Find the header
        $header = PressActualHeader::findOrFail($headerId);

        // Get all details associated with the header
        $details = PressActualFormDetail::where('header_id', $headerId)->get();

        // Loop through details and delete associated productions and ng records
        foreach ($details as $detail) {
            $productions = PressActualFormProduction::where('details_id', $detail->id)->get();

            foreach ($productions as $production) {
                PressActualFormNg::where('production_id', $production->id)->delete();
                $production->delete();
            }

            $detail->delete();
        }

        // Finally, delete the header
        $header->delete();

        DB::commit();
        return redirect('/daily-report/press')->with('status', 'Daily report deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect('/daily-report/press')->with('failed', 'Failed to delete daily report. Please try again. Error: ' . $e->getMessage());
    }
}

    public function exportExcel(Request $request){
        $month = $request->input('month');
    return Excel::download(new PressDailyReportExport($month), "press_daily_report_export.xlsx");
    }


}
