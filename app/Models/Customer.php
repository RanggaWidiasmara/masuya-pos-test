<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'province',
        'city',
        'district',
        'village',
        'zip_code',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
