<?php

namespace App\Models;

use App\Enums\RentalTransactionStatus; // Import the enum
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'trx_id',
        'user_id', // Added user_id to fillable
        'product_id',
        'name',
        'phone_number',
        'selected_size',
        'started_at',
        'ended_at',
        'late_days',
        'late_fee',
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
        'status' => RentalTransactionStatus::class, // Cast status to the enum
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
