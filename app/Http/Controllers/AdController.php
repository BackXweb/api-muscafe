<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ad\StoreRequest;
use App\Http\Requests\Ad\UpdateRequest;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function index(Request $request) {
        $query = Ad::where('user_id', $request->user()->id)->where($request->user()->role->name, 'LIKE', 'user.%')->orderBy(request('sort', 'created_at'), request('order', 'desc'))->orderBy('id', 'desc');

        return $this->outputPaginationData(
            ['with_data' => 'Ads found successfully', 'without_data' => 'Ads not found'],
            $query->paginate((int)request('per_page', 15))
        );
    }

    public function show(Request $request) {
        return $this->outputData(
            ['with_data' => 'Ad found successfully', 'without_data' => 'Ad not found'],
            Ad::where('id', $request->id)->where('user_id', $request->user()->id)->where($request->user()->role->name, 'LIKE', 'user.%')->first()
        );
    }

    public function store(StoreRequest $request) {
        $validated = $request->validated();
        $validated['storage'] = Storage::putFile('ads/' . $request->user()->id, $request->file('file'));

        if ($validated['storage'] !== false) {
            Ad::create($validated);

            return $this->outputData(['with_data' => 'Ad created successfully']);
        } else {
            return $this->outputError('Failed upload file', 500);
        }
    }

    public function update(UpdateRequest $request) {
        $validated = $request->validated();
        $ad = Ad::where('id', $request->id)->where('user_id', $request->user()->id)->where($request->user()->role->name, 'LIKE', 'user.%')->first();

        if ($ad) {
            $ad->update($validated);

            return $this->outputData(['without_data' => 'Ad updated successfully']);
        } else {
            return $this->outputData(['without_data' => 'Ad not found']);
        }
    }

    public function destroy(Request $request) {
        $ad = Ad::where('id', $request->id)->where('user_id', $request->user()->id)->where($request->user()->role->name, 'LIKE', 'user.%')->first();

        if ($ad) {
            DB::query('DELETE FROM playlist_to_ad WHERE ad_id = ' . $ad->id);
            $ad->delete();

            return $this->outputData(['without_data' => 'Ad deleted successfully']);
        } else {
            return $this->outputData(['without_data' => 'Ad not found']);
        }
    }
}
