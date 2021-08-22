<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Traits\ApiTrait;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    use ApiTrait;

    public function register(RegisterUserRequest $registerUserRequest)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $registerUserRequest->name,
                'password' => bcrypt($registerUserRequest->password),
                'email' => $registerUserRequest->email
            ]);

            $response = response()->json([
                'success' => true,
                'message' => "",
                'data' => [
                    'token' => JWTAuth::fromUser($user),
                    'user' => $user
                ]
            ], Response::HTTP_OK);
            DB::commit();
            return $response;
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => []
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function login(LoginRequest $loginRequest)
    {
        try {
            if (! $token = auth()->attempt($loginRequest->validated())) {
                return response()->json(['success' => false, 'message' => 'Credentials did not match'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => []
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json([
            'success' => true,
            'message' => '',
            'data' => [
                'user' => auth()->user()
            ]]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['success' => true, 'message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => "",
            'data' => [
                'access_token' => $token,
                'user' => auth()->user(),
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]], Response::HTTP_OK);
    }}
