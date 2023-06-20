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
        $ads = Ad::where('user_id', $request->user()->id)->orderBy(request('sort', 'created_at'), request('order', 'desc'))->orderBy('id', 'desc')->paginate((int)request('per_page', 15));

        foreach ($ads as $key => $ad) {
            $ads[$key]->storage = Storage::url($ads[$key]->storage);
        }

        return $this->outputPaginationData(['with_data' => 'Ads found successfully', 'without_data' => 'Ads not found'], $ads);
    }

    public function show(Request $request) {
        $ad = Ad::where('id', $request->id)->where('user_id', $request->user()->id)->first();

        if (!empty($ad)) {
            $ad->storage = Storage::url($ad->storage);
            return $this->outputData(['with_data' => 'Ad found successfully'], $ad);
        } else {
            return $this->outputData(['without_data' => 'Ad not found']);
        }
    }

    public function store(StoreRequest $request) {
        $validated = $request->validated();
        $validated['storage'] = Storage::putFile('/public/ads/' . $request->user()->id, $request->file('file'), 'public');
        $validated['user_id'] = $request->user()->id;

        if ($validated['storage'] !== false) {
            Ad::create($validated);

            return $this->outputData(['without_data' => 'Ad created successfully']);
        } else {
            return $this->outputError('Failed upload file', 500);
        }
    }

    public function update(UpdateRequest $request) {
        $validated = $request->validated();
        $ad = Ad::where('id', $request->id)->where('user_id', $request->user()->id)->first();

        if ($ad) {
            $ad->update($validated);

            return $this->outputData(['without_data' => 'Ad updated successfully']);
        } else {
            return $this->outputData(['without_data' => 'Ad not found']);
        }
    }

    public function destroy(Request $request) {
        $ad = Ad::where('id', $request->id)->where('user_id', $request->user()->id)->first();

        if ($ad) {
            DB::delete('DELETE FROM playlist_to_ad WHERE ad_id = ' . $ad->id);
            $ad->delete();

            return $this->outputData(['without_data' => 'Ad deleted successfully']);
        } else {
            return $this->outputData(['without_data' => 'Ad not found']);
        }
    }
}
