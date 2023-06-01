<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\StoreRequest;
use App\Http\Requests\Playlist\UpdateRequest;
use App\Models\Playlist;
use App\Models\PlaylistToStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        $query = Playlist::where('user_id', $request->user()->id)->orderBy(request('sort', 'created_at'), request('order', 'desc'))->orderBy('id', 'desc');

        return $this->outputPaginationData(
            ['with_data' => 'Playlists found successfully', 'without_data' => 'Playlists not found'],
            $query->paginate((int)request('per_page', 15))
        );
    }

    public function show(Request $request)
    {
        $playlist = Playlist::where('user_id', $request->user()->id)->where('id', $request->id);

        if ($playlist) {
            return $this->outputPaginationData(['with_data' => 'Playlist found successfully'], $playlist);
        } else {
            return $this->outputData(['without_data' => 'Playlists not found']);
        }
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = $request->user()->id;

        $playlist = Playlist::create($validated);

        $validated['playlist_id'] = $playlist->id;

        PlaylistToStyle::insert($validated['styles']);

        return $this->outputData(['without_data' => 'Create playlist successfully']);
    }

    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();
    }

    public function destroy(Request $request)
    {
        $playlist = Playlist::where('user_id', $request->user()->id)->where('id', $request->id);

        if ($playlist) {
            DB::query('DELETE FROM playlist_to_style WHERE playlist_id = ' . $playlist->id);
            $playlist->delete();

            return $this->outputPaginationData(['with_data' => 'Playlist deleted successfully'], $playlist);
        } else {
            return $this->outputData(['without_data' => 'Playlists not found']);
        }
    }
}
