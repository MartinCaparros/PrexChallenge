<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserStoreRequest;

use App\Http\Resources\User\UserLoginResource;
use App\Http\Resources\User\UserStoreResource;
use App\Interfaces\UserRepositoryInterface;

use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /** @var UserRepositoryInterface $userRepository */
    protected $userRepository = null;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['status' => 'error', 'message' => 'UNAUTHORIZED'], 401);
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('PrexChallenge')->accessToken;
        $data = collect(['user' => $user, 'token' => $token]);

        return response(new UserLoginResource($data), 200);
    }

    public function register(UserStoreRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];

        $user = $this->userRepository->store($data);

        return response(new UserStoreResource($user), 200);
    }
}
