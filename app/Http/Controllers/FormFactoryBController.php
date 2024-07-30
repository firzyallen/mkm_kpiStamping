<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\FactbActualDetail;
use App\Models\FactbActualFormNg;
use App\Models\FactbActualFormProduction;
use App\Models\FactbActualHeader;
use App\Models\FactbMstModel;
use App\Models\FactbMstShop;
use App\Models\Dropdown;
use Carbon\Carbon;

class FormFactoryBController extends Controller
{
    public function index(){
        $items = FactbActualHeader::all();
        $categories = DB::table('dropdowns')->where('category', 'Shift')->get();

        return view('daily-report.factoryb.index', compact('items', 'categories'));
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
        $existingHeader = FactbActualHeader::where('date', $request->date)
                                           ->where('shift', $request->shift)
                                           ->first();
    
        if ($existingHeader) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Daily Report for this date and shift already exists.']);
        }
    
        // Create a new instance of the FactbActualHeader model
        $header = new FactbActualHeader();
        $header->date = $request->date;
        $header->shift = $request->shift;
        $header->pic = $request->pic;
        $header->revision = 0;
        $header->created_by = auth()->user()->name;
    
        // Save the new FactbActualHeader record to the database
        $header->save();
    
        // Redirect back or return a response as needed
        $encryptedId = encrypt($header->id);
        return redirect()->route('form.daily-report.factoryb', ['id' => $encryptedId])->with('status', 'Daily Report header created successfully.');
    }
    

    public function formChecksheet($id) {
        $id = decrypt($id);
        $item = FactbActualHeader::findOrFail($id);
        $shops = FactbMstShop::all();
        $models = FactbMstModel::all();

        $formattedData = [];
        foreach ($models as $model) {
            $shop = $shops->where('id', $model->shop_id)->first();
            $formattedData[] = [
                'shop_name' => $shop->shop_name,
                'model_name' => $model->model_name,
            ];
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
        return view('daily-report.factoryb.form', compact('formattedData', 'item', 'id', 'working_hour'));
    }

    public function storeForm(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $headerId = $request->id;
    
            // Insert data into factb_actual_details table
            foreach ($request->shop as $shop) {
                $imgPath = null;
                if ($request->hasFile("photo_shop.$shop.0")) {
                    $file = $request->file("photo_shop.$shop.0");
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/img/photo_shop/factoryb/shop/');
                    $file->move($destinationPath, $fileName);
                    $imgPath = 'assets/img/photo_shop/factoryb/shop/' . $fileName;
                }
                $shopId = FactbMstShop::where('shop_name', $shop)->value('id');
    
                $detail = FactbActualDetail::create([
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
    
                $detailId = $detail->id;
    
                // Insert data into factb_actual_form_productions table for each model in the shop
                foreach ($request->production as $modelName => $production) {
                    $imgPathNG = null;
                        if ($request->hasFile("photo_ng.$modelName.0")) {
                            $file = $request->file("photo_ng.$modelName.0");
                            $fileName = uniqid() . '_' . $file->getClientOriginalName();
                            $destinationPath = public_path('assets/img/photo_shop/factoryb/ng/');
                            $file->move($destinationPath, $fileName);
                            $imgPathNG = 'assets/img/photo_shop/factoryb/ng/' . $fileName;
                        }
                    $modelId = FactbMstModel::where('model_name', $modelName)->value('id');
                    $modelShopId = FactbMstModel::where('model_name', $modelName)->value('shop_id');
                    if ($modelShopId == $shopId) {
                        $output8 = $production['output8'][0] ?? 0;
                        $output2 = $production['output2'][0] ?? 0;
                        $output1 = $production['output1'][0] ?? 0;
                        $total_prod = $output8 + $output2 + $output1;

                        $plan_prod = $production['plan_prod'][0] ?? $total_prod;
                        if ($plan_prod == 0) {
                            $plan_prod = $total_prod;
                        }
                        $productionId = FactbActualFormProduction::create([
                            'details_id' => $detailId,
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
                        ])->id;
    
                        // Insert NG data into factb_actual_form_ngs table
                        FactbActualFormNg::create([
                            'production_id' => $productionId,
                            'model_id' => $modelId,
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
    
            DB::commit();
            return redirect('/daily-report/factoryb')->with('status', 'Daily report data saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/daily-report/factoryb')->with('failed', 'Failed to save daily report data. Please try again. Error: ' . $e->getMessage());
        }
    }
    

    public function showDetail($id)
    {
        $id = decrypt($id);

        $header = FactbActualHeader::findOrFail($id);
        $details = FactbActualDetail::where('header_id', $id)->get();
        $productions = FactbActualFormProduction::whereIn('details_id', $details->pluck('id'))->get();
        $notGoods = FactbActualFormNg::whereIn('production_id', $productions->pluck('id'))->get();

        $formattedData = [];
        foreach ($details as $detail) {
            $shop = FactbMstShop::find($detail->shop_id);
            $models = $productions->where('details_id', $detail->id);

            $shopData = [
                'shop_name' => $shop->shop_name,
                'manpower' => $detail->manpower,
                'manpower_plan' => $detail->manpower_plan,
                'working_hour' => $detail->working_hour,
                'ot_hour' => $detail->ot_hour,
                'ot_hour_plan' => $detail->ot_hour_plan,
                'notes' => $detail->notes,
                'photo_shop' => $detail->photo_shop,
                'models' => [],
            ];

            foreach ($models as $model) {
                $shopData['models'][] = [
                    'model_id' => $model->model_id,
                    'model_name' => FactbMstModel::where('id', $model->model_id)->value('model_name'),
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

            $formattedData[] = $shopData;
        }

        return view('daily-report.factoryb.show', compact('header', 'formattedData', 'id'));
    }


    public function updateDetail($id)
    {
        $id = decrypt($id);

        $header = FactbActualHeader::findOrFail($id);
        $details = FactbActualDetail::where('header_id', $id)->get();
        $productions = FactbActualFormProduction::whereIn('details_id', $details->pluck('id'))->get();
        $notGoods = FactbActualFormNg::whereIn('production_id', $productions->pluck('id'))->get();

        $formattedData = [];
        foreach ($details as $detail) {
            $shop = FactbMstShop::find($detail->shop_id);
            $models = $productions->where('details_id', $detail->id);

            $shopData = [
                'shop_name' => $shop->shop_name,
                'manpower' => $detail->manpower,
                'manpower_plan' => $detail->manpower_plan,
                'working_hour' => $detail->working_hour,
                'ot_hour' => $detail->ot_hour,
                'ot_hour_plan' => $detail->ot_hour_plan,
                'notes' => $detail->notes,
                'photo_shop' => $detail->photo_shop,
                'models' => [],
            ];

            foreach ($models as $model) {
                $shopData['models'][] = [
                    'model_id' => $model->model_id,
                    'model_name' => FactbMstModel::where('id', $model->model_id)->value('model_name'),
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

            $formattedData[] = $shopData;
        }

        return view('daily-report.factoryb.update', compact('header', 'formattedData', 'id'));
    }


    public function updateForm(Request $request)
    {
        dd($request);
        DB::beginTransaction();

        try {
            $headerId = $request->id;

            // Update factb_actual_details
            foreach ($request->shop as $shop) {
                $imgPath = null;
                if ($request->hasFile("photo_shop.$shop.0")) {
                    $file = $request->file("photo_shop.$shop.0");
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('assets/img/photo_shop/factoryb/shop/');
                    $file->move($destinationPath, $fileName);
                    $imgPath = 'assets/img/photo_shop/factoryb/shop/' . $fileName;
                }
                $shopId = FactbMstShop::where('shop_name', $shop)->value('id');

                $detail = FactbActualDetail::where('header_id', $headerId)->where('shop_id', $shopId)->first();

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
                    $detail = FactbActualDetail::create([
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

                // Update factb_actual_form_productions
                foreach ($request->production as $modelName => $production) {
                    $modelId = FactbMstModel::where('model_name', $modelName)->value('id');
                    $modelShopId = FactbMstModel::where('model_name', $modelName)->value('shop_id');

                    if ($modelShopId == $shopId) {
                        $output8 = $production['output8'][0] ?? 0;
                        $output2 = $production['output2'][0] ?? 0;
                        $output1 = $production['output1'][0] ?? 0;
                        $total_prod = $output8 + $output2 + $output1;

                        $plan_prod = $production['plan_prod'][0] ?? $total_prod;
                        if ($plan_prod == 0) {
                            $plan_prod = $total_prod;
                        }
                        $productionRecord = FactbActualFormProduction::where('details_id', $detail->id)->where('model_id', $modelId)->first();

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
                            $productionRecord = FactbActualFormProduction::create([
                                'details_id' => $detail->id,
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
                        }

                        $totalProd = ($production['output8'][0] ?? 0) + ($production['output2'][0] ?? 0) + ($production['output1'][0] ?? 0);

                        // Update factb_actual_form_ngs
                        $imgPathNG = null;
                        if ($request->hasFile("photo_ng.$modelName.0")) {
                            $file = $request->file("photo_ng.$modelName.0");
                            $fileName = uniqid() . '_' . $file->getClientOriginalName();
                            $destinationPath = public_path('assets/img/photo_shop/factoryb/ng/');
                            $file->move($destinationPath, $fileName);
                            $imgPathNG = 'assets/img/photo_shop/factoryb/ng/' . $fileName;
                        }
                        $ng = $request->production[$modelName];
                        $ngRecord = FactbActualFormNg::where('production_id', $productionRecord->id)->first();
                        
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
                            FactbActualFormNg::create([
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

            DB::commit();
            return redirect('/daily-report/factoryb')->with('status', 'Daily report data updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/daily-report/factoryb')->with('failed', 'Failed to update daily report data. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // Decrypt the header ID
            $headerId = decrypt($id);

            // Find the header
            $header = FactbActualHeader::findOrFail($headerId);

            // Get all details associated with the header
            $details = FactbActualDetail::where('header_id', $headerId)->get();

            // Loop through details and delete associated productions and ng records
            foreach ($details as $detail) {
                $productions = FactbActualFormProduction::where('details_id', $detail->id)->get();

                foreach ($productions as $production) {
                    FactbActualFormNg::where('production_id', $production->id)->delete();
                    $production->delete();
                }

                $detail->delete();
            }

            // Finally, delete the header
            $header->delete();

            DB::commit();
            return redirect('/daily-report/factoryb')->with('status', 'Daily report deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/daily-report/factoryb')->with('failed', 'Failed to delete daily report. Please try again. Error: ' . $e->getMessage());
        }
    }

}

