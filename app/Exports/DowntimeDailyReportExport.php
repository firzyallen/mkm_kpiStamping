<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DowntimeDailyReportExport implements FromView, ShouldAutoSize
{
    protected $month;

    public function __construct($month)
    {
        $this->month = Carbon::parse($month)->format('m');
    }

    public function view(): View
    {

        $dailyReportData = DB::table('downtime_form_headers as dh')
        ->leftJoin('unified_sections as us', 'us.id', '=', 'dh.section_id')
        ->leftJoin('downtime_form_details as dd', 'dh.id', '=', 'dd.header_id')
        ->leftJoin('unified_shops as ush', 'ush.id', '=', 'dd.shop_id')
        ->leftJoin('downtime_form_actuals as da', 'da.details_id', '=', 'dd.id')
        ->leftJoin('downtime_mst_machines as dmm', 'dmm.id', '=', 'da.machine_id')
        ->select(
            'dh.date',
            'dh.shift',
            'us.section_name',
            'dd.reporter',
            'ush.shop_name',
            'dmm.machine_name',
            'da.category',
            'da.problem',
            'da.cause',
            'da.action',
            'da.judgement',
            'da.start_time',
            'da.end_time',
            'da.balance',
            'da.percentage as downtime_percent'
        )
        ->whereMonth('dh.date', $this->month)
        ->orderBy('dh.date')
        ->orderBy('ush.shop_name')
        ->get();

        $dailyReportData->transform(function ($item) {
            $item->start_time = Carbon::parse($item->start_time)->format('H:i');
            $item->end_time = $item->end_time ? Carbon::parse($item->end_time)->format('H:i') : null;
            return $item;
        });

        return view('exports.downtime_daily_report', [
            'dailyReportData' => $dailyReportData
        ]);
    }
}