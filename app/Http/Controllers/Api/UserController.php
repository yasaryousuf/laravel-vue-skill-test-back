<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\UserRepository;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ApiTrait;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        try {
            return $this->successApiResponse(UserResource::collection($this->userRepository->getAll()), "All user data");
        } catch (\Exception $e) {
            return $this->failureApiResponse($e);
        }
    }


    public function store(UserCreateRequest $userCreateRequest)
    {
        DB::beginTransaction();
        try {
            $this->userRepository->save($userCreateRequest->validated());
            DB::commit();
            return $this->successApiResponseSaveData("User saved successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failureApiResponse($e);
        }
    }

    public function show($id)
    {
        try {
            return $this->successApiResponse(new UserResource($this->userRepository->getOne($id)), "Single user data");
        } catch (\Exception $e) {
            return $this->failureApiResponse($e);
        }
    }

    public function update($id, UserUpdateRequest $userUpdateRequest)
    {
        DB::beginTransaction();
        try {
            $this->userRepository->update($id, $userUpdateRequest->validated());
            DB::commit();
            return $this->successApiResponseSaveData("User updated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failureApiResponse($e);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userRepository->delete($id);
            return $this->successApiResponseSaveData("User deleted successfully.");
        } catch (\Exception $e) {
            return $this->failureApiResponse($e);
        }
    }
}
