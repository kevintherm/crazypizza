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
use Stripe\StripeClient;

class CouponController extends Controller
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

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

            $coupon = Coupon::firstOrCreate(['id' => $request->input('id')], $request->only(['code', 'discount', 'quota', 'is_active']));

            if ($coupon->quota != $request->input('quota')) {
                return ApiResponse::error('Cannot update quota after creation. Please create a new coupon with the desired quota.', null, 400);
            }

            $coupon->update($request->only(['code', 'discount', 'quota', 'is_active']));

            if (!$coupon->stripe_coupon_id) {
                $stripeCoupon = $this->stripe->coupons->create([
                    'duration' => 'once',
                    'max_redemptions' => $coupon->quota > 0 ? $coupon->quota : 1,
                    'name' => "Discount: {$coupon->code}",
                    'amount_off' => $coupon->discount->toInt(),
                    'currency' => config('app.currency', 'usd'),
                ]);

                $coupon->stripe_coupon_id = $stripeCoupon->id;
                $coupon->save();
            }

            $coupon->refresh();

            return ApiResponse::success("Coupon with code {$coupon->code} saved successfully.", $coupon);
        });
    }

    public function delete(Request $request)
    {
        return $this->safe(function () use ($request) {
            $coupon = CrudService::delete(Coupon::class, $request->id, $request->column);

            if ($coupon->stripe_coupon_id) {
                $this->stripe->coupons->delete($coupon->stripe_coupon_id);
            }

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
