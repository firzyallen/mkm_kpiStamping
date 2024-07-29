<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PressMstShop;
use App\Models\PressMstModel;

class MstPressController extends Controller
{
    // Methods for PressMstShop

    public function indexShop()
    {
        $shops = PressMstShop::get();
        return view('masterpress.shop', compact('shops'));
    }

    public function storeShop(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'shop_name' => 'required|array',
            'shop_name.*' => 'required|string|max:255',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Loop through each shop name
            foreach ($request->shop_name as $shopName) {
                // Check if the shop_name already exists
                $existingShop = PressMstShop::where('shop_name', $shopName)->first();

                if ($existingShop) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Shop "' . $shopName . '" already exists.');
                }

                // Create a new instance of PressMstShop model
                $shop = new PressMstShop();
                $shop->shop_name = $shopName;

                // Save the new shop to the database
                $shop->save();
            }

            // Commit the transaction
            DB::commit();

            // Redirect back with a success message
            return redirect()->back()->with('status', 'Shops created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create shops.');
        }
    }

    public function updateShop(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id' => 'required|integer|exists:press_mst_shops,id',
            'shop_name' => 'required|string|max:255',
        ]);

        // Find the shop instance by its ID
        $shop = PressMstShop::findOrFail($request->id);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update the shop attributes only if they have been modified
            if ($shop->shop_name !== $request->shop_name) {
                $shop->shop_name = $request->shop_name;
            }

            // Check if any attributes have been modified
            if ($shop->isDirty()) {
                // Save the updated record
                $shop->save();

                // Commit the transaction
                DB::commit();

                // Redirect back with a success message
                return redirect()->back()->with('status', 'Shop updated successfully.');
            }

            // No changes detected, redirect back with a message
            DB::commit();
            return redirect()->back()->with('failed', 'No changes detected.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update shop.');
        }
    }

    // Methods for PressMstModel

    public function indexModel()
    {
        $models = PressMstModel::with('shop')->get();
        $shops = PressMstShop::all(); // This is needed for the form
        return view('masterpress.model', compact('models', 'shops'));
    }

    public function storeModel(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'model' => 'required|array',
            'model.*' => 'required|string|max:255',
            'shop_id' => 'required|exists:press_mst_shops,id',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Loop through each model name
            foreach ($request->model as $modelName) {
                // Check if the model_name already exists within the same shop
                $existingModel = PressMstModel::where('model_name', $modelName)
                    ->where('shop_id', $request->shop_id)
                    ->first();

                if ($existingModel) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Model "' . $modelName . '" already exists within the same shop.');
                }

                // Create a new instance of PressMstModel model
                $model = new PressMstModel();
                $model->model_name = $modelName;
                $model->shop_id = $request->shop_id;

                // Save the new model to the database
                $model->save();
            }

            // Commit the transaction
            DB::commit();

            // Redirect back with a success message
            return redirect()->back()->with('status', 'Models created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create models.');
        }
    }

    public function updateModel(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id' => 'required|integer|exists:press_mst_models,id',
            'model_name' => 'required|string|max:255',
            'shop_id' => 'required|exists:press_mst_shops,id',
        ]);

        // Find the model instance by its ID
        $model = PressMstModel::findOrFail($request->id);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update the model attributes only if they have been modified
            if ($model->model_name !== $request->model_name || $model->shop_id !== $request->shop_id) {
                $model->model_name = $request->model_name;
                $model->shop_id = $request->shop_id;
            }

            // Check if any attributes have been modified
            if ($model->isDirty()) {
                // Save the updated record
                $model->save();

                // Commit the transaction
                DB::commit();

                // Redirect back with a success message
                return redirect()->back()->with('status', 'Model updated successfully.');
            }

            // No changes detected, redirect back with a message
            DB::commit();
            return redirect()->back()->with('failed', 'No changes detected.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update model.');
        }
    }
}
