<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser; // Add this import
use Filament\Panel; // Add this import

class User extends Authenticatable implements FilamentUser // Implement FilamentUser
{
    use HasFactory, Notifiable;

    // Add the canAccessPanel method
    public function canAccessPanel(Panel $panel): bool
    {
        // Only allow users with the 'admin' role to access the Filament panel
        return $this->role === 'admin';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'google_token',
        'phone_number',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the rental transactions for the user.
     */
    public function rentalTransactions()
    {
        return $this->hasMany(RentalTransaction::class);
    }

    /**
     * Get the custom transactions for the user.
     */
    public function customTransactions()
    {
        return $this->hasMany(CustomTransaction::class);
    }
}
