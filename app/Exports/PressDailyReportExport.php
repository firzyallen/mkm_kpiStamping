<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PressDailyReportExport implements FromView, ShouldAutoSize
{
    protected $month;

    public function __construct($month)
    {
        $this->month = Carbon::parse($month)->format('m');
    }

    public function view(): View
    {
        $dailyReportData = DB::table('press_actual_headers as ph')
            ->leftJoin('press_actual_form_details as pd', 'ph.id', '=', 'pd.header_id')
            ->leftJoin('press_mst_shops as ps', 'pd.shop_id', '=', 'ps.id')
            ->leftJoin('press_actual_form_productions as pp', 'pd.id', '=', 'pp.details_id')
            ->leftJoin('press_mst_models as pm', 'pp.model_id', '=', 'pm.id')
            ->leftJoin('press_actual_form_ngs as pn', 'pp.id', '=', 'pn.production_id')
            ->select(
                'ph.date',
                'ph.shift',
                'ph.created_by as PIC',
                'ps.shop_name',
                'pm.model_name as production_model_name',
                'pd.manpower',
                'pd.manpower_plan',
                'pd.working_hour',
                'pd.notes',
                'pd.photo_shop',
                'pp.prod_process',
                'pp.status',
                'pp.type',
                'pp.inc_material',
                'pp.machine',
                'pp.setting',
                'pp.hour_from',
                'pp.hour_to',
                'pp.plan_prod',
                'pp.OK',
                'pn.OK as ng_OK',
                'pn.rework',
                'pn.dmg_part',
                'pn.dmg_rm',
                'pn.remarks as ng_remarks',
                'pn.photo_ng'
            )
            ->whereMonth('ph.date', $this->month)
            ->orderBy('ph.date')
            ->orderBy('ps.shop_name')
            ->get();

        return view('exports.press_daily_report', [
            'dailyReportData' => $dailyReportData
        ]);
    }
}
