<?php
namespace App\Models;
use App\Enums\CustomTransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CustomTransaction extends Model
{
use HasFactory;
protected $fillable = [
'trx_id',
'user_id',
'name',
'phone_number',
'image_reference',
'image_reference_2',
'image_reference_3',
'material',
'color',
'selected_size_chart',
'lebar_bahu_belakang',
'lingkar_panggul',
'lingkar_pinggul',
'lingkar_dada',
'kerung_lengan',
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
'status' => CustomTransactionStatus::class,
];

public function setAdminPriceAttribute($value)
{
    $this->attributes['admin_price'] = $value;
    if ($this->status === CustomTransactionStatus::PENDING && !is_null($value)) {
        $this->attributes['status'] = CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION;
    }
}
public function getProgressStepIndex(): int { return match ($this->status) { CustomTransactionStatus::PENDING, CustomTransactionStatus::REJECTED, CustomTransactionStatus::CANCELLED => 0, CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION, CustomTransactionStatus::PAYMENT_VALIDATED => 1, CustomTransactionStatus::IN_PROGRESS => 3, CustomTransactionStatus::COMPLETED => 4, default => 0, }; }
public function user()
{
    return $this->belongsTo(User::class);
}
}
