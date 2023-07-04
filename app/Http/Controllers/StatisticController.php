<?php

namespace App\Http\Controllers;

use App\Http\Requests\Statistic\StoreRequest;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatisticController extends Controller
{
    public function index(Request $request) {
        $statistics = Statistic::where('facility_id', $request->id)->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime(request('date_start', Carbon::today()))))->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime(request('date_end', Carbon::yesterday()))));

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
}
