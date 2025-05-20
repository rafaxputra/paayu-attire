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
        'name',
        'phone_number',
        'image_reference',
        'kebaya_preference',
        'amount_to_buy',
        'date_needed',
        'delivery_type', // Added delivery_type
        'address', // Added address
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
        if ($this->status === 'accepted' && !is_null($value)) {
            $this->attributes['status'] = 'in_progress';
        }
    }
}
