<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
            Log::error('[Product][getList]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[Product][getList]: ' . $e->getMessage());
            Log::error('[Product][getList]: ' . $e->getLine());

            return response()->json([
                'data' => null,
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function get(Product $product)
    {
        return response()->json(['product' => $product->append(['product_category', 'image', 'product_stats_data'])]);
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

            Log::error('[Product][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[Product][store]: ' . $e->getMessage());
            Log::error('[Product][store]: ' . $e->getLine());

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

            Log::error('[Product][update]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[Product][update]: ' . $e->getMessage());
            Log::error('[Product][update]: ' . $e->getLine());

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

            Log::error('[Product][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[Product][destroy]: ' . $e->getMessage());
            Log::error('[Product][destroy]: ' . $e->getLine());

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

    public function getProductsForSales(Request $request)
    {
        $requestData = $request->only(['search_key', 'category_id']);

        $stockSub = DB::table('product_stocks')
            ->select('product_id', DB::raw('SUM(quantity) as total_stock'))
            ->whereNull('deleted_at')
            ->groupBy('product_id');

        $salesSub = DB::table('product_sales')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereNull('deleted_at')
            ->groupBy('product_id');

        $productModel = Product::select([
            'products.id',
            'products.name',
            'products.image_extension',
            'products.image_name',
            DB::raw('products.selling_price as price'),
            'products.unit',
            DB::raw('COALESCE(stock.total_stock, 0) - COALESCE(sales.total_sold, 0) as stock')
        ])->leftJoinSub($stockSub, 'stock', function($join) {
            $join->on('stock.product_id', '=', 'products.id');
        })->leftJoinSub($salesSub, 'sales', function($join) {
            $join->on('sales.product_id', '=', 'products.id');
        });

        if (!empty($requestData['search_key'])) {
            $productModel->where('products.name', 'LIKE', $requestData['search_key'] . '%');
        }

        if (!empty($requestData['category_id'])) {
            $productModel->where('products.product_category_id', $requestData['category_id']);
        }

        return response()->json([
            'data' => $productModel->get()->append(['image']),
        ]);
    }
}
