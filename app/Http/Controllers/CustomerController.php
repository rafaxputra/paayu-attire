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

        // Fetch rental transactions for the logged-in user
        $rentalTransactions = RentalTransaction::where('name', $user->name)
                                                ->where('phone_number', $user->phone_number) // Assuming phone_number is also stored on the user or can be linked
                                                ->with('product')
                                                ->latest()
                                                ->get();

        // Fetch custom transactions for the logged-in user
        $customTransactions = CustomTransaction::where('name', $user->name)
                                                ->where('phone_number', $user->phone_number) // Assuming phone_number is also stored on the user or can be linked
                                                ->latest()
                                                ->get();

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
            'phone_number' => ['nullable', 'string', 'max:255'], // Phone number is optional
        ]);

        $user->update($request->only(['name', 'phone_number']));

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
}
