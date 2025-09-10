<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\DataTableResource;
use App\Http\Responses\ApiResponse;
use App\Models\Order;
use App\Services\CrudService;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function dataTable(Request $request)
    {
        return $this->safe(function () use ($request) {
            $pizzas = DataTableService::make(Order::class, $request, ['invoice_number'], []);
            return ApiResponse::success('Pizzas fetched successfully.', new DataTableResource($pizzas));
        });
    }

    public function createUpdate(OrderRequest $request)
    {
        return $this->safe(function () use ($request) {

            $order = Order::findOrFail($request->input('id'));

            $payload = $request->validated();

            // if ($order->status == 'completed' && $payload['status'] != 'completed') return ApiResponse::error('Completed order cannot be updated');

            $order->update($payload);

            $order->refresh();

            return ApiResponse::success("Order with invoice {$order->invoice_number} saved successfully.", $order);
        });
    }

    public function delete(Request $request)
    {
        return $this->safe(function () use ($request) {
            $order = CrudService::delete(Order::class, $request->id, $request->column);
            return ApiResponse::success("Deleted successfully.", $order);
        });
    }

    public function bulkDelete(Request $request)
    {
        return $this->safe(function () use ($request) {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:orders,id',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error($validator->errors()->first(), errors: $validator->errors(), status: 400);
            }

            CrudService::bulkDelete(Order::class, $request->ids);
            return ApiResponse::success("Orders deleted successfully.", null, 200);
        });
    }
}
