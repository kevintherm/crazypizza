<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public const STATUS = [
        'pending' => 'pending',
        'shipped' => 'shipped',
        'completed' => 'completed',
        'cancelled' => 'cancelled'
    ];

    public static function generateInvoiceNumber()
    {
        $year = date('Y');

        // Find the last invoice created this year
        $lastInvoice = Order::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            // Extract the number part (last 3 digits)
            $lastNumber = intval(substr($lastInvoice->invoice_number, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "INV-{$year}-{$newNumber}";
    }

}
