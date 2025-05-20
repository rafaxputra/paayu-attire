<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'trx_id',
        'product_id',
        'name',
        'phone_number',
        'started_at',
        'ended_at',
        'delivery_type',
        'address',
        'total_amount',
        'is_paid',
        'payment_proof',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
        'is_paid' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
