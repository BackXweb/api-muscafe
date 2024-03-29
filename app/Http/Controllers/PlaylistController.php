<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\StoreRequest;
use App\Http\Requests\Playlist\UpdateRequest;
use App\Models\Ad;
use App\Models\Playlist;
use App\Models\PlaylistToStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        return $this->outputPaginationData(
            ['with_data' => 'Playlists found successfully', 'without_data' => 'Playlists not found'],
            Playlist::where('user_id', $request->user()->id)->orderBy(request('sort', 'created_at'), request('order', 'desc'))->orderBy('id', 'desc')->paginate((int)request('per_page', 15))
        );
    }

    public function show(Request $request)
    {
        $playlist = Playlist::where('user_id', $request->user()->id)->where('id', $request->id)->first();
        $styles = [];

        if ($playlist) {
            foreach (PlaylistToStyle::where('playlist_id', $playlist->id)->get() as $key => $style) {
                $storage_style = $this->storageUrlToPath($style->storage_style);

                if (Storage::exists($storage_style)) {
                    $styles[$key]['style'] = $style;

                    if (Storage::exists($storage_style. '/config.json')) {
                        $style_content = json_decode(Storage::get($storage_style . '/config.json'));

                        $styles[$key]['style'] = [
                            'name' => $style_content->name,
                            'description' => $style_content->description,
                            'image' => Storage::url($storage_style . '/' . $style_content->image),
                        ];
                    }

                    foreach (Storage::files($this->storageUrlToPath($style->storage_style) . '/music') as $music) {
                        $styles[$key]['musics'][] = Storage::url($music);
                    }
                }
            }

            return $this->outputData(
                ['with_data' => 'Playlist found successfully'],
                [
                    'playlist' => $playlist,
                    'styles' => $styles,
                    'ads' => $playlist->ads()->with(['playlist_to_ad' => function ($query) use ($playlist) {
                        $query->where('playlist_id', $playlist->id);
                    }])->get(),
                ]
            );
        } else {
            return $this->outputData(['without_data' => 'Playlist not found']);
        }
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $playlist = Playlist::create($validated);

        $playlist->playlist_to_style()->createMany($validated['styles']);

        if (isset($validated['ads']) && count($validated['ads']) > 0) {
            $playlist->playlist_to_ad()->createMany($validated['ads']);
        }

        return $this->outputData(['without_data' => 'Create playlist successfully']);
    }

    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();
        $playlist = Playlist::where('user_id', $request->user()->id)->where('id', $request->id)->first();

        if ($playlist) {
            $playlist->playlist_to_ad()->delete();
            $playlist->playlist_to_style()->delete();

            $playlist->playlist_to_style()->createMany($validated['styles']);

            if (count($validated['ads']) > 0) {
                $playlist->playlist_to_ad()->createMany($validated['ads']);
            }

            $playlist->update($validated);

            return $this->outputData(['without_data' => 'Playlist updated successfully']);
        } else {
            return $this->outputData(['without_data' => 'Playlists not found']);
        }
    }

    public function destroy(Request $request)
    {
        $playlist = Playlist::where('user_id', $request->user()->id)->where('id', $request->id)->first();

        if ($playlist) {
            $playlist->playlist_to_ad()->delete();
            $playlist->playlist_to_style()->delete();

            $playlist->facilities()->update(['playlist_id' => NULL]);

            $playlist->delete();

            return $this->outputData(['without_data' => 'Playlist deleted successfully']);
        } else {
            return $this->outputData(['without_data' => 'Playlists not found']);
        }
    }
}
