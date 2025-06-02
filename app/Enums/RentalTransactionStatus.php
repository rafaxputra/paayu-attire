<?php 
namespace App\Enums; 
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
enum RentalTransactionStatus: string implements HasLabel, HasColor
{
case PENDING_PAYMENT_VERIFICATION = 'pending_payment_verification'; // Initial state: customer uploaded payment, awaiting admin verification
case PAYMENT_VALIDATED = 'payment_validated'; // Payment verified by admin (now includes ready for pickup)
case PAYMENT_FAILED = 'payment_failed'; // Admin marked payment as invalid
case IN_RENTAL = 'in_rental'; // Item is with the customer
case COMPLETED = 'completed'; // Transaction fully completed (includes item returned)
case REJECTED = 'rejected'; // Admin rejected the booking (e.g., item unavailable)
case CANCELLED = 'cancelled'; // User cancelled the booking
public function getLabel(): ?string
{
    return match ($this) {
        self::PENDING_PAYMENT_VERIFICATION => 'Menunggu Verifikasi Pembayaran',
        self::PAYMENT_VALIDATED => 'Pembayaran Terverifikasi',
        self::PAYMENT_FAILED => 'Pembayaran Gagal/Tidak Valid',
        self::IN_RENTAL => 'Dalam Penyewaan',
        self::COMPLETED => 'Selesai',
        self::REJECTED => 'Ditolak',
        self::CANCELLED => 'Dibatalkan',
    };
}

public function getColor(): string | array | null
{
    return match ($this) {
        self::PENDING_PAYMENT_VERIFICATION => Color::Orange,
        self::PAYMENT_VALIDATED => Color::Green,
        self::PAYMENT_FAILED => Color::Red,
        self::IN_RENTAL => Color::Yellow,
        self::COMPLETED => Color::Green,
        self::REJECTED => Color::Red,
        self::CANCELLED => Color::Gray,
    };
}

}