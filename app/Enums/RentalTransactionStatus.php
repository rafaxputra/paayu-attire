<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RentalTransactionStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case PENDING_PAYMENT = 'pending_payment';
    case PAID = 'paid'; // Status after payment is verified
    case IN_RENTAL = 'in_rental'; // Status when the item is with the customer
    case COMPLETED = 'completed'; // Status after the item is returned
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACCEPTED => 'Accepted',
            self::REJECTED => 'Rejected',
            self::PENDING_PAYMENT => 'Pending Payment',
            self::PAID => 'Paid',
            self::IN_RENTAL => 'In Rental',
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
            self::PENDING_PAYMENT => Color::Orange,
            self::PAID => Color::Green,
            self::IN_RENTAL => Color::Yellow,
            self::COMPLETED => Color::Green, // Maybe a different shade of green or blue?
            self::CANCELLED => Color::Gray,
        };
    }
}