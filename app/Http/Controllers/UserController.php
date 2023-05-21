<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;

use App\Models\User;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('login', $validated['login'])->with('role')->first();

        if ($user && Hash::check($validated['password'], $user->password)) {
            return $this->outputData(
                ['with_data' => 'Login success'],
                ['token' => $user->createToken($user->login, [$user->role->name])->plainTextToken]
            );
        } else {
            throw ValidationException::withMessages(['login' => ['The provided credentials are incorrect.'],]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->outputData(['without_data' => 'Logout success']);
    }

    public function index()
    {
        return $this->outputData(
            [
                'with_data' => 'Users found successfully',
                'without_data' => 'Users not found'
            ],
            User::whereHas('role', function ($query) {
                $query->where('name', 'user');
            })->get()
        );
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $validated['role_id'] = Role::where('name', 'user')->first()->id;
        $validated['password'] = Hash::make($validated['password']);
        if (isset($request->subscribe_end) && !empty($request->subscribe_end))
            $validated['subscribe_end'] = date('Y-m-d H:i:s', strtotime($validated['subscribe_end']));

        User::create($validated);

        return $this->outputData(['without_data' => 'Create user successfully'],);
    }

    public function show(Request $request)
    {
        $user = User::where('id', $request->id)->whereHas('role', function ($query) {
            $query->where('name', 'user');
        })->first();

        if ($user) {
            return $this->outputData(['with_data' => 'User found successfully'], $user);
        } else {
            return $this->outputData(['without_data' => 'User not found']);
        }
    }

    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('id', $request->id)->whereHas('role', function ($query) {
            $query->where('name', 'user');
        })->first();

        if ($user) {
            if ($user->login !== $validated['login'])
                if (User::where('login', $validated['login'])->first())
                    throw ValidationException::withMessages(['login' => ['The login has already been taken.']]);

            if (!Hash::check($validated['password'], $user->password)) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            if (!empty($validated['subscribe_end']))
                $validated['subscribe_end'] = date('Y-m-d H:i:s', strtotime($request->subscribe_end));

            $user->update($validated);

            return $this->outputData(['without_data' => 'User updated successfully']);
        } else {
            return $this->outputData(['without_data' => 'User not found']);
        }
    }

    public function destroy(Request $request) {
        $user = User::where('id', $request->id)->whereHas('role', function ($query) {
            $query->where('name', 'user');
        })->first();

        if ($user) {
            $user->delete();
            return $this->outputData(['without_data' => 'User deleted']);
        } else {
            return $this->outputData(['without_data' => 'User not found']);
        }
    }
}
