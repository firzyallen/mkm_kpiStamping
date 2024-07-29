<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\FactbHpu;
use App\Models\FactbOtdp;
use App\Models\FactbFtt;
use App\Models\FactbMstShop;
use App\Models\FactbShopDetail;
use App\Models\FactbNgDetail;
use Carbon\Carbon;

class FactoryBKPIController extends Controller
{
    public function index(Request $request)
    {
        $shops = FactbMstShop::all();

        $currentMonth = $request->input('month', Carbon::now()->month);
        $currentYear = $request->input('year', Carbon::now()->year);

        $kpiData = [];

        foreach ($shops as $shop) {
            $hpuData = DB::table('factb_hpus')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_id', $shop->id)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });

            $otdpData = DB::table('factb_otdps')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_id', $shop->id)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });

            $fttData = DB::table('factb_ftts')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_id', $shop->id)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });

            $kpiData[$shop->shop_name] = [
                'hpu' => $hpuData,
                'otdp' => $otdpData,
                'ftt' => $fttData
            ];
        }

        $shopDetails = FactbShopDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $ngDetails = FactbNgDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $monthName = Carbon::createFromDate(null, $currentMonth)->format('F');

        return view('kpi.index', compact('shops', 'kpiData', 'shopDetails', 'ngDetails', 'monthName', 'currentYear', 'currentMonth'));
    }
}
