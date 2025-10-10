<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use App\Services\ProductCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductCategoriesController extends Controller
{
    private $service;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->service = $productCategoryService;
    }

    public function getList(Request $request)
    {
        $params = $request->only(['start', 'records_per_page', 'search']);

        try {
            list($data, $total) = $this->service->getDTList($params);

            return response()->json(['data' => $data, 'total_records' => $total], 200);
        } catch (\Exception $e) {
            Log::error('[ProductCategory][getList]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[ProductCategory][getList]: ' . $e->getMessage());
            Log::error('[ProductCategory][getList]: ' . $e->getLine());

            return response()->json([
                'data' => null,
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function get(ProductCategory $productCategory)
    {
        return response()->json(['product_category' => $productCategory]);
    }

    public function store(StoreProductCategoryRequest $request)
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

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategories)
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

    public function destroy(ProductCategory $productCategory)
    {
        Gate::authorize('destroy', $productCategory);

        try {
            DB::beginTransaction();

            $filename = $productCategory['image_name'] . '.' . $productCategory['image_extension'];
            $filePath = $this->service::IMAGE_BASE_PATH . $filename;
            if ($productCategory->delete() && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            DB::commit();

            return response()->json([
                'message' => 'Category record successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[ProductCategory][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[ProductCategory][destroy]: ' . $e->getMessage());
            \Log::error('[ProductCategory][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function getDropdownList(Request $request)
    {
        $data = [];

        $search = $request->query('search');
        if (!empty($search)) {
            $data = ProductCategory::select('id', 'name')
                ->where('name', 'like', "%{$search}%")
                ->take(5)
                ->get();
        }

        return response()->json($data);
    }
}
