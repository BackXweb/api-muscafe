<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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
            if (($user->status && ($user->role->name === 'manager' || empty($user->subscribe_end))) || ($user->status && $user->subscribe_end > date('Y-m-d'))) {
                return $this->outputData(
                    ['with_data' => 'Login success'],
                    ['token' => $user->createToken($user->login, explode('.', $user->role->name))->plainTextToken, 'role' => $user->role->name, 'name' => $user->name]
                );
            } else {
                return $this->outputError('Login denied', 403);
            }
        } else {
            throw ValidationException::withMessages(['login' => ['The provided credentials are incorrect']]);
        }
    }

    public function login_manager(Request $request)
    {
        $user = User::where('id', $request->id)->with('role')->first();

        if ($user) {
            $request->user()->currentAccessToken()->delete();

            return $this->outputData(
                ['with_data' => 'Login success'],
                ['token' => $user->createToken($user->login, explode('.', $user->role->name))->plainTextToken, 'role' => $user->role->name, 'name' => $user->name]
            );
        } else {
            return $this->outputData(['without_data' => 'User not found']);
        }
    }

    public function reset_link(Request $request)
    {
        $user = User::whereHas('role', function ($query) {
            $query->where('name', 'LIKE', 'user.%');
        })->where('id', $request->id)->with('role')->first();

        if ($user) {
            $validated['reset_token'] = Str::random(30);
            $user->update($validated);

            return $this->outputData(
                ['with_data' => 'Token for reset password created successfully'],
                ['token' => $validated['reset_token']]
            );
        } else {
            return $this->outputData(['without_data' => 'User not found']);
        }
    }

    public function check_reset_link(Request $request)
    {
        $user = User::whereHas('role', function ($query) {
            $query->where('name', 'LIKE', 'user.%');
        })->where('reset_token', $request->reset_token)->first();

        if ($user) {
            return response()->json(['message' => 'User found successfully', 'data' => true]);
        } else {
            return response()->json(['message' => 'User not found', 'data' => false]);
        }
    }

    public function reset_password(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
        $user = User::whereHas('role', function ($query) {
            $query->where('name', 'LIKE', 'user.%');
        })->where('reset_token', $request->reset_token)->with('role')->first();

        if ($user) {
            $validated['reset_token'] = null;
            $validated['password'] = Hash::make($validated['password']);

            $user->update($validated);

            return $this->outputData(
                ['with_data' => 'Password reset successfully'],
                ['token' => $user->createToken($user->login, explode('.', $user->role->name))->plainTextToken, 'role' => $user->role->name, 'name' => $user->name]
            );
        } else {
            return $this->outputData(['without_data' => 'User not found']);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->outputData(['without_data' => 'Logout success']);
    }

    public function index(Request $request)
    {
        $query = User::whereHas('role', function ($query) {
            $query->where('name', 'LIKE', request('role', 'user.%'));

            if (request('role', 0))
                $query->where('id', request('role', 0));
        })->with('manager', function ($query) {
            $query->select(['id', 'name']);
        })->with('role', function ($query) {
            $query->select(['id', 'name']);
        })->orderBy(request('sort', 'created_at'), request('order', 'desc'))->orderBy('id', 'desc');

        if (!empty($request->status) || $request->status === '0') {
            $query->where('status', $request->status);
        }

        if (!empty($request->manager_id)) {
            $query->where('manager_id', $request->manager_id);
        }

        if (request('subscribe_end', 1)) {
            $query->whereNotNull('subscribe_end');
        } else {
            $query->whereNull('subscribe_end');
        }

        return $this->outputPaginationData(
            ['with_data' => 'Users found successfully', 'without_data' => 'Users not found'],
            $query->paginate((int)request('per_page', 15))
        );
    }

    public function show(Request $request)
    {
        $user = User::where('id', $request->id)->whereHas('role', function ($query) {
            $query->where('name', 'LIKE', 'user.%');
        })->first();

        if ($user) {
            return $this->outputData(['with_data' => 'User found successfully'], $user);
        } else {
            return $this->outputData(['without_data' => 'User not found']);
        }
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        if (!empty($validated['subscribe_end'])) {
            $validated['subscribe_end'] = date('Y-m-d H:i:s', strtotime($validated['subscribe_end']));
        }

        User::create($validated);

        return $this->outputData(['without_data' => 'Create user successfully']);
    }

    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('id', $request->id)->whereHas('role', function ($query) {
            $query->where('name', 'LIKE', 'user.%');
        })->first();

        if ($user) {
            if (!empty($validated['login']) && $user->login !== $validated['login']) {
                if (User::where('login', $validated['login'])->first()) {
                    throw ValidationException::withMessages(['login' => ['The login has already been taken']]);
                }
            }

            if (!empty($validated['subscribe_end'])) {
                $validated['subscribe_end'] = date('Y-m-d H:i:s', strtotime($request->subscribe_end));
            }

            $user->update($validated);

            return $this->outputData(['without_data' => 'User updated successfully']);
        } else {
            return $this->outputData(['without_data' => 'User not found']);
        }
    }

    public function destroy(Request $request)
    {
        $user = User::where('id', $request->id)->whereHas('role', function ($query) {
            $query->where('name', 'LIKE', 'user.%');
        })->first();

        if ($user) {
            DB::query('DELETE FROM personal_access_tokens WHERE name = ' . $user->login);
            $user->delete();

            return $this->outputData(['without_data' => 'User deleted']);
        } else {
            return $this->outputData(['without_data' => 'User not found']);
        }
    }

    public function get_user(Request $request) {
        return $this->outputData(['with_data' => 'User data'], User::where('id', $request->user()->id)->with('role')->first());
    }
}
