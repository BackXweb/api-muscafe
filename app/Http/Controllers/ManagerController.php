<?php

namespace App\Http\Controllers;

use App\Http\Requests\Manager\StoreRequest;
use App\Http\Requests\Manager\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        return $this->outputPaginationData(
            ['with_data' => 'Managers found successfully', 'without_data' => 'Managers not found'],
            User::whereHas('role', function ($query) {
                $query->where('name', 'manager');
            })->paginate((int)$request->per_page)
        );
    }

    public function show(Request $request)
    {
        return $this->outputData(
            ['with_data' => 'Manager found successfully', 'without_data' => 'Manager not found'],
            User::where('id', $request->id)->whereHas('role', function ($query) {
                $query->where('name', 'manager');
            })->first()
        );
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $validated['role_id'] = Role::where('name', 'manager')->first()->id;
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return $this->outputData(['without_data' => 'Create manager successfully'],);
    }

    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('id', $request->id)->whereHas('role', function ($query) {
            $query->where('name', 'manager');
        })->first();

        if ($user) {
            if (!empty($validated['login']) && $user->login !== $validated['login']) {
                if (User::where('login', $validated['login'])->first()) {
                    throw ValidationException::withMessages(['login' => ['The login has already been taken']]);
                }
            }

            if (!empty($validated['password'])) {
                if (!Hash::check($validated['password'], $user->password)) {
                    $validated['password'] = Hash::make($validated['password']);
                } else {
                    unset($validated['password']);
                }
            }

            $user->update($validated);

            return $this->outputData(['without_data' => 'Manager updated successfully']);
        } else {
            return $this->outputData(['without_data' => 'Manager not found']);
        }
    }

    public function destroy(Request $request)
    {
        $user = User::where('id', $request->id)->whereHas('role', function ($query) {
            $query->where('name', 'manager');
        })->first();

        if ($user) {
            if ($user->id === $request->user()->id) {
                return $this->outputData(['without_data' => 'You cannot delete the manager you are logged in as']);
            } else {
                DB::query('DELETE FROM personal_access_tokens WHERE name = ' . $user->login);
                $user->delete();

                return $this->outputData(['without_data' => 'Manager deleted']);
            }
        } else {
            return $this->outputData(['without_data' => 'Manager not found']);
        }
    }
}
