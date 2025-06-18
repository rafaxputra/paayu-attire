<?php

namespace App\Http\Controllers;

use App\Models\CustomTransaction;
use App\Models\RentalTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function customerDashboard()
    {
        $user = Auth::user();

        // Fetch rental transactions for the logged-in user using the relationship
        $rentalTransactions = $user->rentalTransactions()->with('product')->latest()->get();

        // Fetch custom transactions for the logged-in user using the relationship
        $customTransactions = $user->customTransactions()->latest()->get();

        return view('front.customer.dashboard', compact('rentalTransactions', 'customTransactions'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('front.customer.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('front.customer.dashboard')->with('success', 'Profile updated successfully.');
    }

    public function deleteAccount()
    {
        $user = Auth::user();

        // Log out the user before deleting the account
        Auth::logout();

        // Delete the user's account
        $user->delete();

        return redirect()->route('front.index')->with('success', 'Your account has been deleted successfully.');
    }

    public function unlinkGoogle(Request $request)
    {
        $user = Auth::user();
        $user->google_id = null;
        $user->google_token = null;
        $user->save();

        // Redirect ke edit profile dengan pesan wajib ganti password
        return redirect()->route('front.customer.editProfile')->with('info', 'Akun Google berhasil di-unlink. Anda harus mengganti password sebelum bisa login ulang.');
    }

    public function showSetPasswordForm()
    {
        $user = Auth::user();
        return view('front.customer.set-password', compact('user'));
    }

    public function setPassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('front.customer.dashboard')->with('success', 'Password set successfully.');
    }
}
