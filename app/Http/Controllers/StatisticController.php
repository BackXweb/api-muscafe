<?php

namespace App\Http\Controllers;

use App\Http\Requests\Statistic\StoreRequest;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatisticController extends Controller
{
    public function index(Request $request) {
        return $this->outputData(
            ['with_data' => 'Statistics found successfully', 'without_data' => 'Statistics not found'],
            Statistic::where('facility_id', $request->user()->id)->whereDate('created_at', '<=', request('date-start', Carbon::today()->format('Y-m-d H:i:s')))->whereDate('created_at', '>=', request('date-end', Carbon::yesterday()->format('Y-m-d H:i:s')))->orderBy('created_at', 'asc')->get()
        );
    }

    public function store(StoreRequest $request) {
        $validated = $request->validated();
        $validated['facility_id'] = $request->user()->id;

        Statistic::create($validated);

        return $this->outputData(['without_data' => 'Music added in statistic successfully']);
    }
}
