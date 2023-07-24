<?php

namespace App\Http\Controllers;

use App\Http\Requests\Facility\StoreRequest;
use App\Http\Requests\Facility\UpdateRequest;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::where('user_id', $request->user()->id)->where('name', 'LIKE', '%' . request('name') . '%');

        if (!empty($request->playlist_id)) {
            $query->where('playlist_id', $request->playlist_id);
        }

        $query->orderBy(request('sort', 'created_at'), request('order', 'desc'))->orderBy('id', 'desc');

        return $this->outputPaginationData(
            ['with_data' => 'Facilities found successfully', 'without_data' => 'Facilities not found'],
            $query->paginate((int)$request->per_page)
        );
    }

    public function show(Request $request)
    {
        return $this->outputData(
            ['with_data' => 'Facility found successfully', 'without_data' => 'Facility not found'],
            Facility::where('user_id', $request->user()->id)->where('id', $request->id)->first()
        );
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $facility = Facility::create($validated);

        $validated['token'] = Crypt::encryptString($facility->createToken($request->user()->login . '_facility_' . $facility->id, ['player'])->plainTextToken);

        $facility->update($validated);

        return $this->outputData(['without_data' => 'Facility create successfully']);
    }

    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();
        $facility = Facility::where('user_id', $request->user()->id)->where('id', $request->id)->first();

        if ($facility) {
            $facility->update($validated);

            return $this->outputData(['without_data' => 'Facility updated successfully']);
        } else {
            return $this->outputData(['without_data' => 'Facility not found']);
        }
    }

    public function destroy(Request $request)
    {
        $facility = Facility::where('user_id', $request->user()->id)->where('id', $request->id)->first();

        if ($facility) {
            $facility->tokens()->delete();
            $facility->statistics()->delete();
            $facility->delete();

            return $this->outputData(['without_data' => 'Facility deleted']);
        } else {
            return $this->outputData(['without_data' => 'Facility not found']);
        }
    }
}
