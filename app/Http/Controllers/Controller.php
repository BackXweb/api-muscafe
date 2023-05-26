<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use function PHPUnit\Framework\returnArgument;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $storage = 'https://api.cvan.ru/storage/';

    protected function getDomain() {
        return ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    }

    protected function outputData($message, $data = []) {
        $data = collect($data);

        if ($data->isNotEmpty() && $data->count() > 0)
            return response()->json(['message' => $message['with_data'], 'data' => $data], 200);
        else
            return response()->json(['message' => $message['without_data'], 'data' => $data], 200);
    }

    protected function outputPaginationData($message, $data = []) {
        $data = collect($data);

        if (!empty($data['data']) && count($data['data']) > 0)
            return response()->json(['message' => $message['with_data'], 'data' => $data], 200);
        else
            return response()->json(['message' => $message['without_data'], 'data' => $data], 200);
    }

    protected function outputError($message, $code) {
        return response()->json(['message' => $message], $code);
    }

    protected function getFilename($path) {
        $filename = explode('/', $path);
        return array_pop($filename);
    }

    protected function getSortData($sort) {
        return explode('.', $sort);
    }
}
