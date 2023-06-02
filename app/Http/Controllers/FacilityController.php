<?php

namespace App\Http\Controllers;

use App\Http\Requests\Facility\StoreRequest;
use App\Http\Requests\Facility\UpdateRequest;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        return $this->outputPaginationData(
            ['with_data' => 'Facilities found successfully', 'without_data' => 'Facilities not found'],
            Facility::where('user_id', $request->user()->id)->where($request->user()->role->name, 'LIKE', 'user.%')->orderBy(request('sort', 'created_at'), request('order', 'desc'))->orderBy('id', 'desc')->paginate((int)$request->per_page)
        );
    }

    public function show(Request $request)
    {
        return $this->outputData(
            ['with_data' => 'Facility found successfully', 'without_data' => 'Facility not found'],
            Facility::where('user_id', $request->user()->id)->where('id', $request->id)->where($request->user()->role->name, 'LIKE', 'user.%')->first()
        );
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        if (in_array('user', $request->user()->currentAccessToken()->abilities)) {
            $validated['user_id'] = $request->user()->id;

            dd($validated);

            Facility::create($validated);

            return $this->outputData(['without_data' => 'Facility create successfully']);
        } else {
            return $this->outputError('Forbidden', 403);
        }
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
            $facility->delete();

            return $this->outputData(['without_data' => 'Facility deleted']);
        } else {
            return $this->outputData(['without_data' => 'Facility not found']);
        }
    }
}
