<?php

namespace App\Http\Controllers;

use App\Models\ProductSale;
use App\Services\ProductSaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductSalesController extends Controller
{
    private $service;

    public function __construct(ProductSaleService $productSaleService)
    {
        $this->service = $productSaleService;
    }

    public function getList(Request $request)
    {
        $params = $request->only(['start', 'records_per_page', 'search', 'sale_id']);

        try {
            list($data, $total) = $this->service->getDTList($params);

            return response()->json(['data' => $data, 'total_records' => $total], 200);
        } catch (\Exception $e) {
            Log::error('[ProductSale][getList]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[ProductSale][getList]: ' . $e->getMessage());
            Log::error('[ProductSale][getList]: ' . $e->getLine());

            return response()->json([
                'data' => null,
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function getProductSalesBreakdownList(Request $request)
    {
        $params = $request->validate(['sale_id' => ['required', 'exists:sales,id']]);

        $select = [
            'product_sales.id',
            'products.unit',
            DB::raw('products.name AS product_name'),
            'product_sales.price',
            'product_sales.quantity',
            'product_sales.subtotal',
        ];

        $data = DB::table('product_sales')
            ->select($select)
            ->where('product_sales.sale_id', $params['sale_id'])
            ->leftJoin('products', 'product_sales.product_id', '=', 'products.id')
            ->whereNull('product_sales.deleted_at')
            ->whereNull('products.deleted_at')
            ->orderBy('product_sales.created_at', 'DESC')
            ->get();

        return response()->json(['data' => $data,], 200);
    }
}
