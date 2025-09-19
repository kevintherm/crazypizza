<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\DataTableResource;
use App\Http\Responses\ApiResponse;
use App\Models\Coupon;
use App\Models\Order;
use App\Services\CrudService;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function dataTable(Request $request)
    {
        return $this->safe(function () use ($request) {
            $coupons = DataTableService::make(Coupon::class, $request, ['code'], []);
            return ApiResponse::success('Coupons fetched successfully.', new DataTableResource($coupons));
        });
    }

    public function createUpdate(CouponRequest $request)
    {
        return $this->safe(function () use ($request) {

            $coupon = Coupon::updateOrCreate(
                ['id' => $request->input('id')],
                $request->only(['code', 'discount', 'quota', 'is_active'])
            );

            $coupon->refresh();

            return ApiResponse::success("Coupon with code {$coupon->code} saved successfully.", $coupon);
        });
    }

    public function delete(Request $request)
    {
        return $this->safe(function () use ($request) {
            $coupon = CrudService::delete(Coupon::class, $request->id, $request->column);
            return ApiResponse::success("Deleted successfully.", $coupon);
        });
    }

    public function bulkDelete(Request $request)
    {
        return $this->safe(function () use ($request) {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:coupons,id',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error($validator->errors()->first(), errors: $validator->errors(), status: 400);
            }

            CrudService::bulkDelete(Coupon::class, $request->ids);
            return ApiResponse::success("Coupons deleted successfully.", null, 200);
        });
    }
}
