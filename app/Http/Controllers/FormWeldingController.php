<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\WeldingActualDetail;
use App\Models\WeldingActualFormNg;
use App\Models\WeldingActualFormProduction;
use App\Models\WeldingActualHeader;
use App\Models\WeldingActualStationDetail;
use App\Models\WeldingMstModel;
use App\Models\WeldingMstShop;
use App\Models\WeldingMstStation;
use App\Models\Dropdown;
use Carbon\Carbon;

class FormWeldingController extends Controller
{
    public function index(){
        $items = WeldingActualHeader::all();
        $categories = DB::table('dropdowns')->where('category', 'Shift')->get();

        return view('daily-report.welding.index', compact('items', 'categories'));
    }

    public function storeMain(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'shift' => 'required|string|max:255',
            'date' => 'required|date',
            'pic' => 'required|string|max:255',
        ]);

        // Check for existing data with the same date and shift
        $existingHeader = WeldingActualHeader::where('date', $request->date)
                                            ->where('shift', $request->shift)
                                            ->first();

        if ($existingHeader) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Daily Report for this date and shift already exists.']);
        }

        // Create a new instance of the WeldingActualHeader model
        $header = new WeldingActualHeader();
        $header->date = $request->date;
        $header->shift = $request->shift;
        $header->pic = $request->pic;
        $header->created_by = auth()->user()->name;

        // Save the new WeldingActualHeader record to the database
        $header->save();

        // Redirect back or return a response as needed
        $encryptedId = encrypt($header->id);
        return redirect()->route('form.daily-report.welding', ['id' => $encryptedId])->with('status', 'Daily Report header created successfully.');
    }


    public function formChecksheet($id) {
        $id = decrypt($id);
        $item = WeldingActualHeader::findOrFail($id);
        $shops = WeldingMstShop::all();
        $stations = WeldingMstStation::all();
    
        $formattedData = [];
        foreach ($shops as $shop) {
            $shopData = [
                'shop_name' => $shop->shop_name,
                'stations' => [],
            ];
            
            $shopStations = $stations->where('shop_id', $shop->id);
            foreach ($shopStations as $station) {
                $stationData = [
                    'station_name' => $station->station_name,
                    'models' => [],
                ];
                
                $models = WeldingMstModel::where('station_id', $station->id)->get();
                foreach ($models as $model) {
                    $stationData['models'][] = [
                        'model_name' => $model->model_name,
                    ];
                }
                
                $shopData['stations'][] = $stationData;
            }

            if($item->shift == 'Night'){
                $working_hour = 6.75;
            }
            elseif($item->shift =='Day'){
                $carbonDate = Carbon::parse($item->date);
                if ($carbonDate->isFriday()) {
                    $working_hour = 7;
                } else {
                    $working_hour = 7.58;
                }
            }
            
            $formattedData[] = $shopData;
        }
    
        return view('daily-report.welding.form', compact('formattedData', 'item', 'id', 'working_hour'));
    }
    
    public function storeForm(Request $request)
    {
        DB::beginTransaction();

        try {
            $headerId = $request->id;

            // Insert data into welding_actual_details table
            foreach ($request->shop as $shop) {
                $imgPath = null;
                if ($request->hasFile("photo_shop.$shop.0")) {
                    $file = $request->file("photo_shop.$shop.0");
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/img/photo_shop/welding/shop/');
                    $file->move($destinationPath, $fileName);
                    $imgPath = 'assets/img/photo_shop/welding/shop/' . $fileName;
                }
                $shopId = WeldingMstShop::where('shop_name', $shop)->value('id');

                $detail = WeldingActualDetail::create([
                    'header_id' => $headerId,
                    'shop_id' => $shopId,
                    'manpower' => $request->manpower[$shop][0] ?? 0,
                    'manpower_plan' => $request->manpower_plan[$shop][0] ?? 0,
                    'working_hour' => $request->working_hour[$shop][0] ?? 0,
                    'ot_hour' => $request->ot_hour[$shop][0] ?? 0,
                    'ot_hour_plan' => $request->ot_hour_plan[$shop][0] ?? 0,
                    'notes' => $request->notes[$shop][0] ?? null,
                    'photo_shop' => $imgPath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $detailId = $detail->id;

                // Retrieve all stations for the current shop
                $stations = WeldingMstStation::where('shop_id', $shopId)->get();

                foreach ($stations as $station) {
                    $stationName = $station->station_name;

                    if (!isset($request->manpower_station[$stationName])) {
                        throw new \Exception('Manpower data is missing for station: ' . $stationName);
                    }

                    $stationDetail = WeldingActualStationDetail::create([
                        'details_id' => $detailId,
                        'station_id' => $station->id,
                        'manpower_station' => $request->manpower_station[$stationName] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $stationDetailId = $stationDetail->id;

                    // Ensure production data is available for the station
                    if (!isset($request->production)) {
                        throw new \Exception('Production data is missing for station: ' . $stationName);
                    }

                    foreach ($request->production as $modelName => $production) {

                        $output8 = $production['output8'][0] ?? 0;
                        $output2 = $production['output2'][0] ?? 0;
                        $output1 = $production['output1'][0] ?? 0;
                        $total_prod = $output8 + $output2 + $output1;

                        $plan_prod = $production['plan_prod'][0] ?? $total_prod;
                        if ($plan_prod == 0) {
                            $plan_prod = $total_prod;
                        }
                        $modelId = WeldingMstModel::where('model_name', $modelName)->value('id');
                        $modelStationId = WeldingMstModel::where('model_name', $modelName)->value('station_id');

                        if ($modelStationId == $station->id) {
                            $productionRecord = WeldingActualFormProduction::create([
                                'station_details_id' => $stationDetailId,
                                'model_id' => $modelId,
                                'hour' => $production['hour'][0] ?? null,
                                'output8' => $production['output8'][0] ?? null,
                                'output2' => $production['output2'][0] ?? null,
                                'output1' => $production['output1'][0] ?? null,
                                'plan_prod' => $plan_prod,
                                'cabin' => $production['cabin'][0] ?? null,
                                'PPM' => $production['PPM'][0] ?? null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            // Insert NG data into welding_actual_form_ngs table
                            $imgPathNG = null;
                            if ($request->hasFile("photo_ng.$modelName.0")) {
                                $file = $request->file("photo_ng.$modelName.0");
                                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                                $destinationPath = public_path('assets/img/photo_shop/welding/ng/');
                                $file->move($destinationPath, $fileName);
                                $imgPathNG = 'assets/img/photo_shop/welding/ng/' . $fileName;
                            }
                            WeldingActualFormNg::create([
                                'production_id' => $productionRecord->id,
                                'total_prod' => ($production['output8'][0] ?? 0) + ($production['output2'][0] ?? 0) + ($production['output1'][0] ?? 0),
                                'reject' => $production['reject'][0] ?? null,
                                'rework' => $production['rework'][0] ?? null,
                                'remarks' => $production['remarks'][0] ?? null,
                                'photo_ng' => $imgPathNG,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect('/daily-report/welding')->with('status', 'Daily report data saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->withInput()->withErrors(['failed' => 'Failed to save daily report data. Please try again. Error: ' . $e->getMessage()]);
        }
    }

    public function showDetail($id)
    {
        $id = decrypt($id);

        $header = WeldingActualHeader::findOrFail($id);
        $details = WeldingActualDetail::where('header_id', $id)->get();
        $stationDetails = WeldingActualStationDetail::whereIn('details_id', $details->pluck('id'))->get();
        $productions = WeldingActualFormProduction::whereIn('station_details_id', $stationDetails->pluck('id'))->get();
        $notGoods = WeldingActualFormNg::whereIn('production_id', $productions->pluck('id'))->get();

        $formattedData = [];
        foreach ($details as $detail) {
            $shop = WeldingMstShop::find($detail->shop_id);

            $shopData = [
                'shop_name' => $shop->shop_name,
                'manpower' => $detail->manpower,
                'manpower_plan' => $detail->manpower_plan,
                'working_hour' => $detail->working_hour,
                'ot_hour' => $detail->ot_hour,
                'ot_hour_plan' => $detail->ot_hour_plan,
                'notes' => $detail->notes,
                'photo_shop' => $detail->photo_shop,
                'stations' => [],
            ];

            $shopStations = $stationDetails->where('details_id', $detail->id);
            foreach ($shopStations as $stationDetail) {
                $station = WeldingMstStation::find($stationDetail->station_id);
                $models = $productions->where('station_details_id', $stationDetail->id);

                $stationData = [
                    'station_name' => $station->station_name,
                    'manpower_station' => $stationDetail->manpower_station,
                    'models' => [],
                ];

                foreach ($models as $model) {
                    $stationData['models'][] = [
                        'model_id' => $model->model_id,
                        'model_name' => WeldingMstModel::where('id', $model->model_id)->value('model_name'),
                        'hour' => $model->hour,
                        'output8' => $model->output8,
                        'output2' => $model->output2,
                        'output1' => $model->output1,
                        'plan_prod' => $model->plan_prod,
                        'cabin' => $model->cabin,
                        'PPM' => $model->PPM,
                        'reject' => $notGoods->where('production_id', $model->id)->first()->reject ?? null,
                        'rework' => $notGoods->where('production_id', $model->id)->first()->rework ?? null,
                        'remarks' => $notGoods->where('production_id', $model->id)->first()->remarks ?? null,
                        'photo_ng' => $notGoods->where('production_id', $model->id)->first()->photo_ng ?? null,
                    ];
                }

                $shopData['stations'][] = $stationData;
            }

            $formattedData[] = $shopData;
        }

        return view('daily-report.welding.show', compact('header', 'formattedData', 'id'));
    }

    public function updateDetail($id)
    {
        $id = decrypt($id);

        $header = WeldingActualHeader::findOrFail($id);
        $details = WeldingActualDetail::where('header_id', $id)->get();
        $stationDetails = WeldingActualStationDetail::whereIn('details_id', $details->pluck('id'))->get();
        $productions = WeldingActualFormProduction::whereIn('station_details_id', $stationDetails->pluck('id'))->get();
        $notGoods = WeldingActualFormNg::whereIn('production_id', $productions->pluck('id'))->get();

        $formattedData = [];
        foreach ($details as $detail) {
            $shop = WeldingMstShop::find($detail->shop_id);

            $shopData = [
                'shop_name' => $shop->shop_name,
                'manpower' => $detail->manpower,
                'manpower_plan' => $detail->manpower_plan,
                'working_hour' => $detail->working_hour,
                'ot_hour' => $detail->ot_hour,
                'ot_hour_plan' => $detail->ot_hour_plan,
                'notes' => $detail->notes,
                'photo_shop' => $detail->photo_shop,
                'stations' => [],
            ];

            $shopStations = $stationDetails->where('details_id', $detail->id);
            foreach ($shopStations as $stationDetail) {
                $station = WeldingMstStation::find($stationDetail->station_id);
                $models = $productions->where('station_details_id', $stationDetail->id);

                $stationData = [
                    'station_name' => $station->station_name,
                    'manpower_station' => $stationDetail->manpower_station,
                    'models' => [],
                ];

                foreach ($models as $model) {
                    $stationData['models'][] = [
                        'model_id' => $model->model_id,
                        'model_name' => WeldingMstModel::where('id', $model->model_id)->value('model_name'),
                        'hour' => $model->hour,
                        'output8' => $model->output8,
                        'output2' => $model->output2,
                        'output1' => $model->output1,
                        'plan_prod' => $model->plan_prod,
                        'cabin' => $model->cabin,
                        'PPM' => $model->PPM,
                        'reject' => $notGoods->where('production_id', $model->id)->first()->reject ?? null,
                        'rework' => $notGoods->where('production_id', $model->id)->first()->rework ?? null,
                        'remarks' => $notGoods->where('production_id', $model->id)->first()->remarks ?? null,
                        'photo_ng' => $notGoods->where('production_id', $model->id)->first()->photo_ng ?? null,
                    ];
                }

                $shopData['stations'][] = $stationData;
            }

            $formattedData[] = $shopData;
        }

        return view('daily-report.welding.update', compact('header', 'formattedData', 'id'));
    }

    public function updateForm(Request $request)
    {
        DB::beginTransaction();

        try {
            $headerId = $request->id;

            // Update welding_actual_details
            foreach ($request->shop as $shop) {
                $imgPath = null;
                if ($request->hasFile("photo_shop.$shop.0")) {
                    $file = $request->file("photo_shop.$shop.0");
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/img/photo_shop/welding/shop/');
                    $file->move($destinationPath, $fileName);
                    $imgPath = 'assets/img/photo_shop/welding/shop/' . $fileName;
                }
                $shopId = WeldingMstShop::where('shop_name', $shop)->value('id');

                $detail = WeldingActualDetail::where('header_id', $headerId)->where('shop_id', $shopId)->first();

                if ($detail) {
                    $detail->update([
                        'manpower' => $request->manpower[$shop][0],
                        'manpower_plan' => $request->manpower_plan[$shop][0],
                        'working_hour' => $request->working_hour[$shop][0],
                        'ot_hour' => $request->ot_hour[$shop][0],
                        'ot_hour_plan' => $request->ot_hour_plan[$shop][0],
                        'notes' => $request->notes[$shop][0] ?? null,
                        'photo_shop' => $imgPath,
                        'updated_at' => now(),
                    ]);
                } else {
                    $detail = WeldingActualDetail::create([
                        'header_id' => $headerId,
                        'shop_id' => $shopId,
                        'manpower' => $request->manpower[$shop][0],
                        'manpower_plan' => $request->manpower_plan[$shop][0],
                        'working_hour' => $request->working_hour[$shop][0],
                        'ot_hour' => $request->ot_hour[$shop][0],
                        'ot_hour_plan' => $request->ot_hour_plan[$shop][0],
                        'notes' => $request->notes[$shop][0] ?? null,
                        'photo_shop' => $imgPath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Update welding_actual_station_details
                $stations = WeldingMstStation::where('shop_id', $shopId)->get();

                foreach ($stations as $station) {
                    $stationName = $station->station_name;

                    $stationDetail = WeldingActualStationDetail::where('details_id', $detail->id)->where('station_id', $station->id)->first();

                    if ($stationDetail) {
                        $stationDetail->update([
                            'manpower_station' => $request->manpower_station[$stationName] ?? 0,
                            'updated_at' => now(),
                        ]);
                    } else {
                        $stationDetail = WeldingActualStationDetail::create([
                            'details_id' => $detail->id,
                            'station_id' => $station->id,
                            'manpower_station' => $request->manpower_station[$stationName] ?? 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $stationDetailId = $stationDetail->id;

                    foreach ($request->production as $modelName => $production) {
                        $output8 = $production['output8'][0] ?? 0;
                        $output2 = $production['output2'][0] ?? 0;
                        $output1 = $production['output1'][0] ?? 0;
                        $total_prod = $output8 + $output2 + $output1;

                        $plan_prod = $production['plan_prod'][0] ?? $total_prod;
                        if ($plan_prod == 0) {
                            $plan_prod = $total_prod;
                        }
                        $modelId = WeldingMstModel::where('model_name', $modelName)->value('id');
                        $modelStationId = WeldingMstModel::where('model_name', $modelName)->value('station_id');

                        if ($modelStationId == $station->id) {
                            $productionRecord = WeldingActualFormProduction::where('station_details_id', $stationDetailId)->where('model_id', $modelId)->first();

                            if ($productionRecord) {
                                $productionRecord->update([
                                    'hour' => $production['hour'][0] ?? null,
                                    'output8' => $production['output8'][0] ?? null,
                                    'output2' => $production['output2'][0] ?? null,
                                    'output1' => $production['output1'][0] ?? null,
                                    'plan_prod' => $plan_prod,
                                    'cabin' => $production['cabin'][0] ?? null,
                                    'PPM' => $production['PPM'][0] ?? null,
                                    'updated_at' => now(),
                                ]);
                            } else {
                                $productionRecord = WeldingActualFormProduction::create([
                                    'station_details_id' => $stationDetailId,
                                    'model_id' => $modelId,
                                    'hour' => $production['hour'][0] ?? null,
                                    'output8' => $production['output8'][0] ?? null,
                                    'output2' => $production['output2'][0] ?? null,
                                    'output1' => $production['output1'][0] ?? null,
                                    'plan_prod' => $production['plan_prod'][0] ?? null,
                                    'cabin' => $production['cabin'][0] ?? null,
                                    'PPM' => $production['PPM'][0] ?? null,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                            $imgPathNG = null;
                            if ($request->hasFile("photo_ng.$modelName.0")) {
                                $file = $request->file("photo_ng.$modelName.0");
                                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                                $destinationPath = public_path('assets/img/photo_shop/welding/ng/');
                                $file->move($destinationPath, $fileName);
                                $imgPathNG = 'assets/img/photo_shop/welding/ng/' . $fileName;
                            }
                            $ng = $request->production[$modelName];
                            $ngRecord = WeldingActualFormNg::where('production_id', $productionRecord->id)->first();
                            
                            $totalProd = ($production['output8'][0] ?? 0) + ($production['output2'][0] ?? 0) + ($production['output1'][0] ?? 0);

                            if ($ngRecord) {
                                $ngRecord->update([
                                    'total_prod' => $totalProd,
                                    'reject' => $ng['reject'][0] ?? null,
                                    'rework' => $ng['rework'][0] ?? null,
                                    'remarks' => $ng['remarks'][0] ?? null,
                                    'photo_ng' => $imgPathNG,
                                    'updated_at' => now(),
                                ]);
                            } else {
                                WeldingActualFormNg::create([
                                    'production_id' => $productionRecord->id,
                                    'model_id' => $modelId,
                                    'total_prod' => $totalProd,
                                    'reject' => $ng['reject'][0] ?? null,
                                    'rework' => $ng['rework'][0] ?? null,
                                    'remarks' => $ng['remarks'][0] ?? null,
                                    'photo_ng' => $imgPathNG,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect('/daily-report/welding')->with('status', 'Daily report data updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['failed' => 'Failed to update daily report data. Please try again. Error: ' . $e->getMessage()]);
        }
    }


}
