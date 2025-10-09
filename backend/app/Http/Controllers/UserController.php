<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function getList(Request $request)
    {
        $params = $request->only(['start', 'records_per_page', 'search']);

        try {
            list($data, $total) = $this->service->getDTList($params);

            return response()->json(['data' => $data, 'total_records' => $total], 200);
        } catch (\Exception $e) {
            Log::error('[User][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[User][store]: ' . $e->getMessage());
            Log::error('[User][store]: ' . $e->getLine());

            return response()->json([
                'data' => null,
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function get(User $user)
    {
        return response()->json(['user' => $user]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = new User();
        Gate::authorize('store', $user);

        try {
            DB::beginTransaction();
            $user = $this->service->createRecord($request->validated(), $user);
            DB::commit();

            return response()->json([
                'message' => 'User record successfully created.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[User][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[User][store]: ' . $e->getMessage());
            Log::error('[User][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        try {
            DB::beginTransaction();
            $user = $this->service->updateRecord($request->validated(), $user);
            DB::commit();

            return response()->json([
                'message' => 'User record successfully updated',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[User][update]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[User][update]: ' . $e->getMessage());
            \Log::error('[User][update]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        Gate::authorize('destroy', $user);

        try {
            DB::beginTransaction();

            $filename = $user['image_name'] . '.' . $user['image_extension'];
            $filePath = $this->service::PROFILE_IMAGE_BASE_PATH . $filename;
            if ($user->delete() && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            DB::commit();

            return response()->json([
                'message' => 'User record successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[User][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[User][destroy]: ' . $e->getMessage());
            \Log::error('[User][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }
}
