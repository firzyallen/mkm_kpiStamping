<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\FactbHpu;
use App\Models\FactbOtdp;
use App\Models\FactbFtt;
use App\Models\FactbMstShop;
use App\Models\FactbShopDetail;
use App\Models\FactbDowntimeDetail;
use App\Models\FactbNgDetail;
use Carbon\Carbon;

class FactoryBKPIController extends Controller
{
    public function index(Request $request)
    {
        $shops = FactbMstShop::all();

        $currentMonth = $request->input('month', Carbon::now()->month);
        $currentYear = $request->input('year', Carbon::now()->year);
        $previousDay = Carbon::now()->subDay();
        $startDate = $request->input('start_date', $previousDay->format('Y-m-d'));
        $endDate = $request->input('end_date', $previousDay->format('Y-m-d'));

        $kpiData = [];

        foreach ($shops as $shop) {
            // Generate date range for the entire month
            $startOfMonth = Carbon::createFromDate($currentYear, $currentMonth, 1);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            $dateRange = $this->generateDateRange($startOfMonth, $endOfMonth);

            // Fetch data for each KPI
            $hpuData = $this->fetchDataWithMissingDates('factb_hpus', $shop->id, $dateRange);
            $otdpData = $this->fetchDataWithMissingDates('factb_otdps', $shop->id, $dateRange);
            $fttData = $this->fetchDataWithMissingDates('factb_ftts', $shop->id, $dateRange);
            $downtimeData = $this->fetchDowntimeDataWithMissingDates('factb_downtimes', $shop->shop_name, $dateRange);

            // Populate kpiData array
            $kpiData[$shop->shop_name] = [
                'hpu' => $hpuData,
                'otdp' => $otdpData,
                'ftt' => $fttData,
                'downtime' => $downtimeData
            ];

            // Compute KPI statuses
            $kpiStatuses[$shop->shop_name]['hpu'] = $this->computeKpiHPUStatus($hpuData->whereBetween('date', [$startDate, $endDate]));
            $kpiStatuses[$shop->shop_name]['otdp'] = $this->computeKpiOTDPStatus($otdpData->whereBetween('date', [$startDate, $endDate]));
            $kpiStatuses[$shop->shop_name]['ftt'] = $this->computeKpiFTTStatus($fttData->whereBetween('date', [$startDate, $endDate]));
            $kpiStatuses[$shop->shop_name]['downtime'] = $this->computeKpiDowntimeStatus($downtimeData->whereBetween('date', [$startDate, $endDate]));
        }

        $shopDetails = FactbShopDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $ngDetails = FactbNgDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $downtimeDetails = FactbDowntimeDetail::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->get();
        $monthName = Carbon::createFromDate(null, $currentMonth)->format('F');

        return view('kpi.index', compact('shops', 'kpiData', 'shopDetails', 'kpiStatuses', 'ngDetails', 'monthName', 'currentYear', 'currentMonth', 'downtimeDetails', 'previousDay', 'startDate', 'endDate'));
    }

    /**
     * Generates a date range for the given start and end date.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return array
     */
    private function generateDateRange(Carbon $start, Carbon $end)
    {
        $dates = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    /**
     * Fetches data from a given table and fills in missing dates with null values.
     *
     * @param string $table
     * @param int $shopId
     * @param array $dateRange
     * @return Collection
     */
    private function fetchDataWithMissingDates($table, $shopId, $dateRange)
    {
        $data = DB::table($table)
            ->whereMonth('date', Carbon::parse($dateRange[0])->month)
            ->whereYear('date', Carbon::parse($dateRange[0])->year)
            ->where('shop_id', $shopId)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item];
            });

        // Fill missing dates with null values
        $filledData = collect($dateRange)->map(function ($date) use ($data, $shopId) {
            if (!isset($data[$date])) {
                return (object)[
                    'date' => $date,
                    'shop_id' => $shopId,
                    'HPU_Plan' => null,
                    'HPU' => null,
                    'FTT_Plan' => null,
                    'FTT' => null,
                    'Downtime_Plan' => null,
                    'Downtime' => null,
                    'OTDP_Plan' => null,
                    'OTDP' => null,
                    'formatted_date' => Carbon::parse($date)->format('j')
                ];
            }

            // Otherwise, return the data with a default structure
            return (object) array_merge((array) $data[$date], [
                'formatted_date' => Carbon::parse($date)->format('j')
            ]);
        });

        return $filledData;
    }

    /**
     * Fetches downtime data based on shop_name and fills in missing dates with null values.
     *
     * @param string $table
     * @param string $shopName
     * @param array $dateRange
     * @return Collection
     */
    private function fetchDowntimeDataWithMissingDates($table, $shopName, $dateRange)
    {
        $data = DB::table($table)
            ->whereMonth('date', Carbon::parse($dateRange[0])->month)
            ->whereYear('date', Carbon::parse($dateRange[0])->year)
            ->where('shop_name', $shopName)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item];
            });

        // Fill missing dates with null values
        $filledData = collect($dateRange)->map(function ($date) use ($data, $shopName) {
            if (!isset($data[$date])) {
                return (object)[
                    'date' => $date,
                    'shop_name' => $shopName,
                    'Downtime_Plan' => null,
                    'Downtime' => null,
                    'formatted_date' => Carbon::parse($date)->format('j')
                ];
            }

            // Otherwise, return the data with a default structure
            return (object) array_merge((array) $data[$date], [
                'formatted_date' => Carbon::parse($date)->format('j')
            ]);
        });

        return $filledData;
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
