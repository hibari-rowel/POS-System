<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $service;

    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
    }

    public function getList(Request $request)
    {
        $params = $request->only(['start', 'records_per_page', 'search']);

        try {
            list($data, $total) = $this->service->getDTList($params);

            return response()->json(['data' => $data, 'total_records' => $total], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function get(Product $product)
    {
        return response()->json(['product' => $product]);
    }

    public function store(StoreProductRequest $request)
    {
        $product = new Product();
        Gate::authorize('store', $product);

        try {
            DB::beginTransaction();
            $product = $this->service->createRecord($request->validated(), $product);
            DB::commit();

            return response()->json([
                'message' => 'Product record successfully created.',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);

        try {
            DB::beginTransaction();
            $product = $this->service->updateRecord($request->validated(), $product);
            DB::commit();

            return response()->json([
                'message' => 'Product record successfully updated.',
                'data' => $product,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        Gate::authorize('destroy', $product);

        try {
            DB::beginTransaction();

            $filename = $product['image_name'] . '.' . $product['image_extension'];
            $filePath = $this->service::IMAGE_BASE_PATH . $filename;
            if ($product->delete() && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            DB::commit();

            return response()->json([]);
        } catch (\Exception $e) {
            DB::rollBack();

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
            $data = Product::select('id', 'name')
                ->where('name', 'like', "%{$search}%")
                ->take(5)
                ->get();
        }

        return response()->json($data);
    }
}
