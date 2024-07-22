<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\FactbMstModel;
use App\Models\FactbMstShop;

class MstFactoryBController extends Controller
{
    public function index(){
        $item = FactbMstShop::get();
        return view('masterfactoryb.shop',compact('item'));
    }
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'shop' => 'required|string|max:255'
        ]);

        // Check if the section already exists in the database
        $existingSection = FactbMstShop::where('shop_name', $request->shop)->exists();

        if ($existingSection) {
            return redirect()->back()->withErrors(['shop' => 'The shop already exists.'])->withInput();
        }

        // Create a new instance of FactBMstModel model and fill it with request data
        $shop = new FactBMstShop();
        $shop->shop_name = $request->shop;

        // Save the new section to the database
        $shop->save();

        // Redirect back or return a response as needed
        // For example:
        return redirect()->back()->with('status', 'Shop created successfully.');
    }

    public function update(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'id' => 'required|integer',
        'shop' => 'required|string|max:255',
    ]);

    // Find the record to update by its ID
    $shop = FactBMstShop::findOrFail($request->id);

    // Update the section attributes only if they have been modified
    if ($shop->shop_name !== $request->shop) {
        $shop->shop_name = $request->shop;
    }

    // Check if any attributes have been modified
    if ($shop->isDirty()) {
        // Save the updated record
        $shop->save();

        // Redirect back or return a response as needed
        // For example:
        return redirect()->back()->with('status', 'Shop updated successfully.');
    }

    // No changes detected, redirect back with a message
    return redirect()->back()->with('failed', 'No changes detected.');
}

    public function indexModel(){
        $item = FactbMstModel::get();
        $shopName = FactbMstShop::get();
        return view('masterfactoryb.model',compact('item','shopName'));
    }

    public function storeModel(Request $request)
    {
        // Validate the incoming request if necessary

        // Extract the shop_id from the request
        $shopId = $request->shop_id;

        // Extract the model values from the request
        $modelNames = $request->model;

        try {
            // Start a transaction
            DB::beginTransaction();

            // Loop through each model name and store it in the mst_factb_model table
            foreach ($modelNames as $modelName) {
                // Check if the model_name already exists
                $existingModel = FactbMstModel::where('model_name', $modelName)->first();

                if ($existingModel) {
                    // If the model_name already exists, rollback the transaction
                    DB::rollBack();
                    throw ValidationException::withMessages(['model_name' => 'Model name already exists.']);
                }

                // Create a new instance of MstShop model
                $model = new FactbMstModel();
                $model->model_name = $modelName;
                $model->shop_id = $shopId;
                $model->save();
            }

            // If everything is successful, commit the transaction
            DB::commit();

            // Redirect back or return a response as needed
            // For example:
            return redirect()->back()->with('status', 'Models stored successfully.');
        } catch (\Exception $e) {
            // If an error occurs, rollback the transaction
            DB::rollBack();

            // Redirect back with an error message
            return redirect()->back()->withErrors(['failed' => $e->getMessage()]);
        }
    }

    public function updateModel(Request $request){
        // Validate the incoming request if necessary

        // Find the shop by its ID
        $model = FactbMstModel::findOrFail($request->id);

        // Update the shop name if it has been changed
        if ($model->model_name !== $request->model) {
            $model->model_name = $request->model;
        }

        // Update the section ID if it has been changed
        if ($model->shop_id != $request->shop_id) {
            $model->shop_id = $request->shop_id;
        }

        // Check if any changes have been made to the model attributes
        if ($model->isDirty()) {
            // Save the changes
            $model->save();

            // Redirect back or return a response as needed
            // For example:
            return redirect()->back()->with('status', 'Model updated successfully.');
        } else {
            // No changes were made
            // Redirect back or return a response indicating that no updates were performed
            // For example:
            return redirect()->back()->with('failed', 'No changes were made.');
        }
    }
}
