<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return $this->outputData(['with_data' => 'Roles found successfully'], Role::where('name', 'LIKE', 'user.%')->get());
    }
}
