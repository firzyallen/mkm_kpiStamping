<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PressHpu;
use App\Models\PressOtdp;
use App\Models\PressFtt;
use App\Models\PressMstShop;
use App\Models\PressShopDetail;
use App\Models\PressDowntimeDetail;
use App\Models\PressNgDetail;
use Carbon\Carbon;

class PressKPIController extends Controller
{
    public function index(Request $request)
    {
        $shops = PressMstShop::all();

        $currentMonth = $request->input('month', Carbon::now()->month);
        $currentYear = $request->input('year', Carbon::now()->year);
        $previousDay = Carbon::now()->subDay();
        $startDate = $request->input('start_date', $previousDay->format('Y-m-d'));
        $endDate = $request->input('end_date', $previousDay->format('Y-m-d'));

        $kpiData = [];

        foreach ($shops as $shop) {
            $hpuData = DB::table('press_hpus')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_id', $shop->id)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });

            $otdpData = DB::table('press_otdps')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_id', $shop->id)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });

            $fttData = DB::table('press_ftts')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_id', $shop->id)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });
            $downtimeData = DB::table('press_downtimes')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_name', $shop->shop_name)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });

            $kpiData[$shop->shop_name] = [
                'hpu' => $hpuData,
                'otdp' => $otdpData,
                'ftt' => $fttData,
                'downtime' => $downtimeData
            ];


            $kpiStatuses[$shop->shop_name]['hpu'] = $this->computeKpiHPUStatus($hpuData->whereBetween('date', [$startDate, $endDate]));
            $kpiStatuses[$shop->shop_name]['otdp'] = $this->computeKpiOTDPStatus($otdpData->whereBetween('date', [$startDate, $endDate]));
            $kpiStatuses[$shop->shop_name]['ftt'] = $this->computeKpiFTTStatus($fttData->whereBetween('date', [$startDate, $endDate]));
            $kpiStatuses[$shop->shop_name]['downtime'] = $this->computeKpiDowntimeStatus($downtimeData->whereBetween('date', [$startDate, $endDate]));
        }
        $shopDetails = PressShopDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $ngDetails = PressNgDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $downtimeDetails = PressDowntimeDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $monthName = Carbon::createFromDate(null, $currentMonth)->format('F');
/* dd($shops,$kpiData,$shopDetails,$kpiStatuses,$ngDetails,$monthName,$currentYear,$currentMonth,$downtimeDetails,$previousDay,$startDate,$endDate); */
        return view('kpi-press.index', compact('shops', 'kpiData', 'shopDetails', 'kpiStatuses', 'ngDetails', 'monthName', 'currentYear', 'currentMonth', 'downtimeDetails', 'previousDay', 'startDate', 'endDate'));
    }

    private function computeKpiHPUStatus($kpiDetails)
    {
        if ($kpiDetails->isEmpty()) {
            return 'grey';
        }

        foreach ($kpiDetails as $detail) {
            if ($detail->HPU > $detail->HPU_Plan) {
                if ($detail->HPU == NULL){

                }
                else {
                return 'red';
                }
            }
        }

        return 'green';
    }

    private function computeKpiOTDPStatus($kpiDetails)
    {
        if ($kpiDetails->isEmpty()) {
            return 'grey';
        }

        foreach ($kpiDetails as $detail) {
            if ($detail->OTDP < $detail->OTDP_Plan) {
                if ($detail->OTDP == NULL){

                }
                else {
                return 'red';
                }
            }
        }

        return 'green';
    }

    private function computeKpiFTTStatus($kpiDetails)
    {
        if ($kpiDetails->isEmpty()) {
            return 'grey';
        }

        foreach ($kpiDetails as $detail) {
            if ($detail->FTT < $detail->FTT_Plan) {
                if ($detail->FTT == NULL){

                }
                else {
                return 'red';
                }
            }
        }

        return 'green';
    }

    private function computeKpiDowntimeStatus($kpiDetails)
        {
            if ($kpiDetails->isEmpty()) {
                return 'grey';
            }

            foreach ($kpiDetails as $detail) {
                if ($detail->Downtime > $detail->Downtime_Plan) {
                    if ($detail->Downtime == NULL){

                    }
                    else {
                    return 'red';
                    }
                }
            }

            return 'green';
        }
}
