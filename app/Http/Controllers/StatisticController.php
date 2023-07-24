<?php

namespace App\Http\Controllers;

use App\Exports\StatisticExport;
use App\Exports\Statistic\PdfExport;
use App\Http\Requests\Statistic\StoreRequest;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class StatisticController extends Controller
{
    public function index(Request $request) {
        $statistics = Statistic::where('facility_id', $request->id)->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime(request('date_start', Carbon::today()))))->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime(request('date_end', Carbon::tomorrow()))));

        if (!empty($request->is_ad) || $request->is_ad === '0') {
            $statistics->where('is_ad', $request->is_ad);
        }

        return $this->outputData(['with_data' => 'Statistics found successfully', 'without_data' => 'Statistics not found'], $statistics->orderBy('created_at', 'asc')->get());
    }

    public function store(StoreRequest $request) {
        $validated = $request->validated();
        $validated['facility_id'] = $request->user()->id;

        Statistic::create($validated);

        return $this->outputData(['without_data' => 'Music added in statistic successfully']);
    }

    public function export(Request $request) {
        if ($request->file_format == 'excel') {
            return Excel::download(new StatisticExport($request), 'statistic_' . date('d.m.Y_H:i:s') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } elseif ($request->file_format == 'pdf') {
            return Excel::download(new StatisticExport($request), 'statistic_' . date('d.m.Y_H:i:s') . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
        } else {
            return $this->outputData(['without_data' => 'Param file_format must by only excel or pdf']);
        }
    }
}
