<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'total_amount' => MoneyCast::class,
        'json' => 'array'
    ];

    public const STATUS = [
        'pending' => 'pending',
        'paid' => 'paid',
        'shipped' => 'shipped',
        'arrived' => 'arrived',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
        // 'failed' => 'failed',
        // 'refunded' => 'refunded',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    public static function generateInvoiceNumber()
    {
        $year = date('Y');

        $lastInvoice = Order::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        $random = strtoupper(Str::random(6));

        return "INV-{$year}-{$newNumber}-{$random}";
    }

}
