<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\PlaylistToStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

class PlayerController extends Controller
{
    public function show(Request $request) {
        $playlist = Playlist::where('id', $request->user()->playlist_id)->first();
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

    public function check_token(Request $request) {
        if (PersonalAccessToken::findToken($request->token)) {
            return response()->json(['message' => 'Token found successfully', 'data' => true], 200);
        } else {
            return response()->json(['message' => 'Token not found', 'data' => false], 401);
        }
    }
}
