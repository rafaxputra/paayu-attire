<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CustomTransactionStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending'; // Pending verification by admin
    case REJECTED = 'rejected'; // Rejected by admin
    case PENDING_PAYMENT_VERIFICATION = 'pending_payment_verification'; // Admin provided estimate, waiting for customer payment proof
    case PAYMENT_FAILED = 'payment_failed'; // Customer payment proof invalid
    case PAYMENT_VALIDATED = 'payment_validated'; // Admin validated payment proof
    case IN_PROGRESS = 'in_progress'; // Payment validated, custom order is being made
    case COMPLETED = 'completed'; // Custom order is finished and ready for pickup
    case CANCELLED = 'cancelled'; // Cancelled by customer or admin

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending Verification',
            self::REJECTED => 'Rejected',
            self::PENDING_PAYMENT_VERIFICATION => 'Pending Payment Verification',
            self::PAYMENT_FAILED => 'Payment Failed',
            self::PAYMENT_VALIDATED => 'Payment Validated',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PENDING => Color::Amber,
            self::REJECTED => Color::Red,
            self::PENDING_PAYMENT_VERIFICATION => Color::Blue,
            self::PAYMENT_FAILED => Color::Red,
            self::PAYMENT_VALIDATED => Color::Green,
            self::IN_PROGRESS => Color::Yellow,
            self::COMPLETED => Color::Green,
            self::CANCELLED => Color::Gray,
        };
    }
}
