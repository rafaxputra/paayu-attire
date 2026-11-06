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
        // Product uses SoftDeletes: without withTrashed() a soft-deleted
        // product resolves to null here and the dashboard / transaction
        // details / success pages 500 reading ->name / ->thumbnail.
        return $this->belongsTo(Product::class)->withTrashed();
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Rental duration in days (ended_at - started_at).
     *
     * This accessor was missing, so every use of ->duration returned null:
     * transactions_details() computed subTotal as price * null = 0, and the
     * re-upload form on the transaction details page submitted an empty
     * duration hidden input.
     */
    public function getDurationAttribute(): int
    {
        if (!$this->started_at || !$this->ended_at) {
            return 0;
        }

        return $this->ended_at->diffInDays($this->started_at);
    }

    public function getLateInfoAttribute()
    {
        if ($this->status === \App\Enums\RentalTransactionStatus::LATE_RETURNED) {
            $lateDays = $this->late_days;
            if ($lateDays === null) {
                // Operands were flipped: now()->diffInDays(ended_at) is
                // negative once ended_at has passed, so it clamped to 0
                // and the late-fee card never showed. ended_at first.
                $lateDays = $this->ended_at->diffInDays(now(), false);
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
