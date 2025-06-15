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

    public function getLateInfoAttribute()
    {
        if ($this->status === \App\Enums\RentalTransactionStatus::LATE_RETURNED) {
            $lateDays = $this->late_days;
            if ($lateDays === null) {
                $lateDays = now()->diffInDays($this->ended_at, false);
                if ($lateDays < 0) $lateDays = 0;
            }
            $lateFee = $this->late_fee;
            if ($lateFee === null && $lateDays > 0) {
                $lateFee = $lateDays * ($this->total_amount * 0.2);
            }
            return [
                'late_days' => $lateDays,
                'late_fee' => $lateFee,
            ];
        }
        return null;
    }
}
