<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Playlist;
use App\Models\PlaylistToStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

class PlayerController extends Controller
{
    public function show(Request $request) {
        $playlist = Playlist::where('id', $request->facility()->playlist_id)->first();
        $styles = [];

        if ($playlist) {
            foreach (PlaylistToStyle::where('playlist_id', $playlist->id)->get() as $key => $style) {
                if (Storage::exists($style->storage)) {
                    $styles[$key]['style'] = $style;

                    foreach (Storage::files($style->storage . '/music') as $music) {
                        $styles[$key]['musics'][] = Storage::url($music);
                    }
                }
            }

            return $this->outputData(
                ['with_data' => 'Playlist found successfully'],
                [
                    'playlist' => $playlist,
                    'styles' => $styles,
                    'ads' => Ad::whereHas('playlist_to_ad', function ($query) use ($playlist) {
                        $query->where('playlist_id', $playlist->id);
                    })->get(),
                ]
            );
        } else {
            return $this->outputData(['without_data' => 'Playlist not found']);
        }
    }

    public function check_token(Request $request) {
        return $this->outputData(['with_data' => 'Token found successfully', 'without_data' => 'Token not found'], PersonalAccessToken::findToken($request->token)->tokenable());
    }
}
