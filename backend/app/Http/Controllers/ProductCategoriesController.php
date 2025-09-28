<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Services\ProductCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProductCategoriesController extends Controller
{
    private $service;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->service = $productCategoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productCategories = new ProductCategory();
        Gate::authorize('store', $productCategories);

        try {
            DB::beginTransaction();
            $user = $this->service->createRecord($request->validated(), $productCategories);
            DB::commit();

            return response()->json([
                'message' => 'Category record successfully created.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[Category][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[Category][store]: ' . $e->getMessage());
            Log::error('[Category][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $productCategories)
    {
        Gate::authorize('update', $productCategories);

        try {
            DB::beginTransaction();
            $user = $this->service->updateRecord($request->validated(), $productCategories);
            DB::commit();

            return response()->json([
                'message' => 'Category record successfully created.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[Category][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[Category][store]: ' . $e->getMessage());
            Log::error('[Category][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategories)
    {
        //
    }
}
