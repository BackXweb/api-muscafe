<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;

use App\Models\User;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['role_id'] = Role::where('name', 'user')->first()->id;

        User::create($validated);

        return $this->outputData(
            ['without_data' => 'Create user successfully'],
        );
    }

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
}
