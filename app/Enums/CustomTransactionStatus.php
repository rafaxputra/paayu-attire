<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CustomTransactionStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case IN_PROGRESS = 'in_progress';
    case PENDING_PAYMENT = 'pending_payment'; // Added pending payment status
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACCEPTED => 'Accepted',
            self::REJECTED => 'Rejected',
            self::IN_PROGRESS => 'In Progress',
            self::PENDING_PAYMENT => 'Pending Payment', // Added label for pending payment
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PENDING => Color::Amber,
            self::ACCEPTED => Color::Blue,
            self::REJECTED => Color::Red,
            self::IN_PROGRESS => Color::Yellow,
            self::PENDING_PAYMENT => Color::Blue, // Changed color to Blue
            self::COMPLETED => Color::Green,
            self::CANCELLED => Color::Gray,
        };
    }
}
