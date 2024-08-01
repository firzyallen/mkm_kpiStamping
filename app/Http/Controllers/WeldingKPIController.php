<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\WeldingHpu;
use App\Models\WeldingOtdp;
use App\Models\WeldingFtt;
use App\Models\WeldingMstShop;
use App\Models\WeldingMstModel;
use App\Models\WeldingMstStation;
use App\Models\WeldingShopDetail;
use App\Models\WeldingDowntimeDetail;
use App\Models\WeldingNgDetail;
use Carbon\Carbon;

class WeldingKPIController extends Controller
{
    public function index(Request $request)
    {
        $shops = WeldingMstShop::all();
        $models = DB::table('welding_mst_models')->get();
        $stations = DB::table('welding_mst_stations')->get();

        $currentMonth = $request->input('month', Carbon::now()->month);
        $currentYear = $request->input('year', Carbon::now()->year);
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $kpiData = [];

        foreach ($shops as $shop) {
            $hpuData = DB::table('welding_hpus')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_id', $shop->id)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });

            /*$otdpData = DB::table('welding_otdps')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_id', $shop->id)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });*/
            $filteredModels = [];
            $filteredStations = [];
            $filteredStations = $stations->filter(function ($station) use ($shop) {
                return $station->shop_id == $shop->id;
            });
            foreach ($filteredStations as $station) {
                $filterModel = $models->filter(function ($model) use ($station) {
                    return $model->station_id == $station->id;
                });
                foreach ($filterModel as $model) {
                    $filteredModels[$model->model_name] = $model;
                }
            }
            foreach ($filteredModels as $model) {
                $otdpData[$model->model_name] = DB::table('welding_model_otdps')
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->where('shop_id', $shop->id)
                    ->where('model_id', $model->id)
                    ->get()
                    ->map(function ($item) {
                        $item->formatted_date = Carbon::parse($item->date)->format('D j');
                        return $item;
                    });
            }


            $fttData = DB::table('welding_ftts')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('shop_id', $shop->id)
                ->get()
                ->map(function ($item) {
                    $item->formatted_date = Carbon::parse($item->date)->format('D j');
                    return $item;
                });

            $downtimeData = DB::table('welding_downtimes')
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
            $otdpData = [];

            $kpiStatuses[$shop->shop_name]['hpu'] = $this->computeKpiHPUStatus($hpuData->whereBetween('date', [$startDate, $endDate]));
            foreach ($filteredModels as $modelotdp) {
                $kpiStatuses[$shop->shop_name]['otdp'][$modelotdp->model_name] = $this->computeKpiOTDPStatus($kpiData[$shop->shop_name]['otdp'][$modelotdp->model_name]->whereBetween('date', [$startDate, $endDate]));
            }
            $kpiStatuses[$shop->shop_name]['ftt'] = $this->computeKpiFTTStatus($fttData->whereBetween('date', [$startDate, $endDate]));
            $kpiStatuses[$shop->shop_name]['downtime'] = $this->computeKpiDowntimeStatus($downtimeData->whereBetween('date', [$startDate, $endDate]));
        }
        $shopDetails = WeldingShopDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $ngDetails = WeldingNgDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $monthName = Carbon::createFromDate(null, $currentMonth)->format('F');
        $downtimeDetails = WeldingDowntimeDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        return view('kpi-welding.index', compact('shops', 'kpiData', 'shopDetails', 'kpiStatuses', 'ngDetails', 'monthName', 'currentYear', 'currentMonth', 'models', 'stations', 'downtimeDetails'));
    }

    private function computeKpiHPUStatus($kpiDetails)
    {
        if ($kpiDetails->isEmpty()) {
            return 'grey';
        }

        foreach ($kpiDetails as $detail) {
            if ($detail->HPU > $detail->HPU_Plan) {
                if ($detail->HPU == NULL) {
                } else {
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
                if ($detail->OTDP == NULL) {
                } else {
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
                if ($detail->FTT == NULL) {
                } else {
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
                if ($detail->Downtime == NULL) {
                } else {
                    return 'red';
                }
            }
        }

        return 'green';
    }
}
