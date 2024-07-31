<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
use Illuminate\Support\Carbon;

class FactoryBDailyReportExport implements FromView, ShouldAutoSize
{
    protected $month;

    public function __construct($month)
    {
        $this->month = Carbon::parse($month)->format('m');
    }

    public function view(): View
    {
        $dailyReportData = DB::table('factb_actual_headers as fah')
            ->leftJoin('factb_actual_details as fad', 'fah.id', '=', 'fad.header_id')
            ->leftJoin('factb_actual_form_productions as fap', 'fad.id', '=', 'fap.details_id')
            ->leftJoin('factb_actual_form_ngs as fan', 'fap.id', '=', 'fan.production_id')
            ->leftJoin('factb_mst_shops as fms', 'fad.shop_id', '=', 'fms.id')
            ->leftJoin('factb_mst_models as fmm', 'fap.model_id', '=', 'fmm.id')
            ->whereMonth('fah.date', $this->month)
            ->select(
                'fah.date',
                'fah.shift',
                'fah.created_by',
                'fms.shop_name',
                'fmm.model_name as production_model_name',
                'fad.manpower as detail_manpower',
                'fad.manpower_plan as detail_manpower_plan',
                'fad.working_hour',
                'fad.ot_hour',
                'fad.ot_hour_plan',
                'fad.notes as detail_notes',
                'fad.photo_shop',
                'fap.hour as production_hour',
                'fap.output8',
                'fap.output2',
                'fap.output1',
                'fap.plan_prod as production_plan_prod',
                'fap.total_prod as production_total_prod',
                'fap.cabin',
                'fap.PPM',
                'fan.total_prod as ng_total_prod',
                'fan.reject as ng_reject',
                'fan.rework as ng_rework',
                'fan.remarks as ng_remarks',
                'fan.photo_ng',
                'fah.created_at',
                'fah.updated_at'
            )
            ->orderBy('fh.date')
            ->orderBy('fs.shop_name')
            ->get();

        return view('exports.factory_b_daily_report', [
            'dailyReportData' => $dailyReportData
        ]);
    }
}
