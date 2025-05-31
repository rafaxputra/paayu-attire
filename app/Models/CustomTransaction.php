<?php

namespace App\Models;

use App\Enums\CustomTransactionStatus; // Import the enum
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'trx_id',
        'user_id', // Added user_id to fillable
        'name',
        'phone_number',
        'image_reference',
        'image_reference_2',
        'image_reference_3',
        'kebaya_preference',
        'amount_to_buy',
        'date_needed',
        'admin_price',
        'admin_estimated_completion_date',
        'status',
        'is_paid',
        'payment_proof',
        'payment_method',
    ];


    protected $casts = [
        'date_needed' => 'date',
        'admin_estimated_completion_date' => 'date',
        'is_paid' => 'boolean',
        'status' => CustomTransactionStatus::class, // Cast status to the enum
    ];

    /**
     * Set the admin_price attribute and update status if necessary.
     *
     * @param  string  $value
     * @return void
     */
    public function setAdminPriceAttribute($value)
    {
        $this->attributes['admin_price'] = $value;

        // If the status is 'accepted' and admin_price is set, change status to 'in_progress'
        // The 'in_progress' status is already in the CustomTransactionStatus enum
        if ($this->status === CustomTransactionStatus::ACCEPTED && !is_null($value)) { // Compare with enum instance
            $this->attributes['status'] = CustomTransactionStatus::IN_PROGRESS; // Use enum case
        }
    }

    /**
     * Get the current step index for the progress indicator.
     *
     * @return int
     */
    public function getProgressStepIndex(): int
    {
        return match ($this->status) {
            CustomTransactionStatus::PENDING, CustomTransactionStatus::REJECTED => 0,
            CustomTransactionStatus::ACCEPTED, CustomTransactionStatus::PENDING_PAYMENT => 1,
            CustomTransactionStatus::IN_PROGRESS => 2,
            CustomTransactionStatus::COMPLETED => 3,
            CustomTransactionStatus::CANCELLED => 0, // Or a different step if needed for cancelled
        };
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
