<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StyleController extends Controller
{
    public function index()
    {
        if (Storage::exists('public/music')) {
            foreach (Storage::directories('public/music') as $directory) {
                if (Storage::exists($directory . '/config.json')) {
                    $json[] = [
                        'id' => last(explode('/', $directory)),
                        'name' => json_decode(Storage::get($directory . '/config.json'))->name
                    ];
                }
            }
        }

        if (isset($json) && count($json) > 0) {
            return $this->outputData(['with_data' => 'Main styles found successfully'], $json);
        } else {
            return $this->outputData(['without_data' => 'Main styles not found']);
        }
    }

    public function show(Request $request)
    {
        if (!empty($request->id) && Storage::exists('public/music/' . $request->id)) {
            foreach (Storage::directories('public/music/' . $request->id) as $style) {
                if (Storage::exists($style . '/config.json')) {
                    $style_content = json_decode(Storage::get($style . '/config.json'));

                    $json[] = [
                        'id' => last(explode('/', $style)),
                        'name' => $style_content->name,
                        'description' => $style_content->description,
                        'image' => Storage::url($style . '/' . $style_content->image),
                        'storage' => Storage::url($style),
                    ];
                }
            }
        }

        if (isset($json) && count($json) > 0) {
            return $this->outputData(['with_data' => 'Style found successfully'], $json);
        } else {
            return $this->outputData(['without_data' => 'Style not found']);
        }
    }
}
