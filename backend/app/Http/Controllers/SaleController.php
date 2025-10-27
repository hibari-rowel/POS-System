<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSalesRequest;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    private $service;

    public function __construct(SaleService $saleService)
    {
        $this->service = $saleService;
    }

    public function store(CreateSalesRequest $request)
    {
        $sale = new Sale();
        Gate::authorize('store', $sale);

        try {
            DB::beginTransaction();
            $sale = $this->service->createRecord($request->validated(), $sale);
            DB::commit();

            return response()->json([
                'message' => 'Sale record successfully created.',
                'data' => $sale
            ], 200);
        }  catch (\Exception $e) {
            DB::rollBack();

            Log::error('[Sale][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[Sale][store]: ' . $e->getMessage());
            Log::error('[Sale][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();
            $sale->delete();
            DB::commit();

            return response()->json([]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[Sale][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            Log::error('[Sale][destroy]: ' . $e->getMessage());
            Log::error('[Sale][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }
}
