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
        'payment_proof',
        'payment_method',
    ];


    protected $casts = [
        'date_needed' => 'date',
        'admin_estimated_completion_date' => 'date',
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

        // If the status is 'pending' and admin_price is set, change status to 'pending_payment_verification'
        if ($this->status === CustomTransactionStatus::PENDING && !is_null($value)) { // Compare with enum instance
            $this->attributes['status'] = CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION; // Use enum case
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
            CustomTransactionStatus::PENDING, CustomTransactionStatus::REJECTED, CustomTransactionStatus::CANCELLED => 0,
            CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION, CustomTransactionStatus::PAYMENT_FAILED => 1,
            CustomTransactionStatus::PAYMENT_VALIDATED => 2,
            CustomTransactionStatus::IN_PROGRESS => 3,
            CustomTransactionStatus::COMPLETED => 4,
        };
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
