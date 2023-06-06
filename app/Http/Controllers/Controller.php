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

    protected function outputData($message, $data = [])
    {
        $data = collect($data);

        if ($data->isNotEmpty() && $data->count() > 0) {
            return response()->json(['message' => $message['with_data'], 'data' => $data], 200);
        } else {
            return response()->json(['message' => $message['without_data'], 'data' => $data], 200);
        }
    }

    protected function outputPaginationData($message, $data = [])
    {
        $data = collect($data);

        if (!empty($data['data']) && count($data['data']) > 0) {
            return response()->json(['message' => $message['with_data'], 'data' => $data], 200);
        } else {
            return response()->json(['message' => $message['without_data'], 'data' => $data], 200);
        }
    }

    protected function outputError($message, $code)
    {
        return response()->json(['message' => $message], $code);
    }

    protected function checkRole($user, $role)
    {
        return in_array($role, $user->currentAccessToken()->abilities);
    }
}
