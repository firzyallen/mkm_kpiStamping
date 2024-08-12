<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class WeldingDailyReportExport implements FromView, ShouldAutoSize
{
    protected $month;

    public function __construct($month)
    {
        $this->month = Carbon::parse($month)->format('m');
    }

    public function view(): View
    {
        $dailyReportData = DB::table('welding_actual_headers as wh')
            ->leftJoin('welding_actual_details as wd', 'wh.id', '=', 'wd.header_id')
            ->leftJoin('welding_mst_shops as ws', 'wd.shop_id', '=', 'ws.id')
            ->leftJoin('welding_actual_station_details as wsd', 'wd.id', '=', 'wsd.details_id')
            ->leftJoin('welding_mst_stations as wms', 'wsd.station_id', '=', 'wms.id')
            ->leftJoin('welding_actual_form_productions as wp', 'wsd.id', '=', 'wp.station_details_id')
            ->leftJoin('welding_mst_models as wm', 'wp.model_id', '=', 'wm.id')
            ->leftJoin('welding_actual_form_ngs as wn', 'wp.id', '=', 'wn.production_id')
            ->select(
                'wh.date',
                'wh.shift',
                'wh.PIC',
                'ws.shop_name',
                'wms.station_name',
                'wm.model_name as production_model_name',
                'wd.manpower',
                'wd.manpower_plan',
                'wd.working_hour',
                'wd.ot_hour',
                'wd.ot_hour_plan',
                'wd.notes',
                'wd.photo_shop',
                'wp.hour',
                'wp.output8',
                'wp.output2',
                'wp.output1',
                'wp.plan_prod',
                'wp.total_prod',
                'wp.cabin',
                'wp.PPM',
                'wn.total_prod as ng_total_prod',
                'wn.reject',
                'wn.rework',
                'wn.remarks as ng_remarks',
                'wn.photo_ng'
            )
            ->whereMonth('wh.date', $this->month)
            ->orderBy('wh.date')
            ->orderBy('ws.shop_name')
            ->get();

        return view('exports.welding_daily_report', [
            'dailyReportData' => $dailyReportData
        ]);
    }
}