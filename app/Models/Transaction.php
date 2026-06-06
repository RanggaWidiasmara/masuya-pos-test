<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'transaction_date',
        'total_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public static function generateInvoiceNo()
    {
        $yymm = now()->format('ym');
        $prefix = 'INV/' . $yymm . '/';

        $lastTransaction = self::where('invoice_no', 'LIKE', $prefix . '%')
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastTransaction) {
            $number = 1;
        } else {
            $lastNumber = intval(substr($lastTransaction->invoice_no, -4));
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
