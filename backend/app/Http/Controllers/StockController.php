<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\ProductStock;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    private $service;

    public function __construct(StockService $stockService)
    {
        $this->service = $stockService;
    }

    public function getList(Request $request)
    {
        $params = $request->only(['start', 'records_per_page', 'search']);

        try {
            list($data, $total) = $this->service->getDTList($params);

            return response()->json(['data' => $data, 'total_records' => $total], 200);
        } catch (\Exception $e) {
            Log::error('[ProductStock][getList]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[ProductStock][getList]: ' . $e->getMessage());
            Log::error('[ProductStock][getList]: ' . $e->getLine());

            return response()->json([
                'data' => null,
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function get(ProductStock $stock)
    {
        return response()->json(['data' => $stock->append(['product'])]);
    }

    public function store(CreateStockRequest $request)
    {
        $stock = new ProductStock();
        Gate::authorize('store', $stock);

        try {
            DB::beginTransaction();
            $stock = $this->service->createRecord($request->validated(), $stock);
            DB::commit();

            return response()->json([
                'message' => 'Stock record successfully created.',
                'data' => $stock
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[ProductStock][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[ProductStock][store]: ' . $e->getMessage());
            Log::error('[ProductStock][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function update(UpdateStockRequest $request, ProductStock $stock)
    {
        Gate::authorize('update', $stock);

        try {
            DB::beginTransaction();
            $stock = $this->service->updateRecord($request->validated(), $stock);
            DB::commit();

            return response()->json([
                'message' => 'Stock record successfully updated.',
                'data' => $stock,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[ProductStock][update]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[ProductStock][update]: ' . $e->getMessage());
            Log::error('[ProductStock][update]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(ProductStock $stock)
    {
        Gate::authorize('destroy', $stock);

        try {
            DB::beginTransaction();

            $stock->delete();

            DB::commit();

            return response()->json([]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[ProductStock][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[ProductStock][destroy]: ' . $e->getMessage());
            Log::error('[ProductStock][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }
}
