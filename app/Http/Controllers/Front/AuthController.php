<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\Log; // Import Log

class AuthController extends Controller
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('front.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect to the intended page or a default page
            return redirect()->intended(route('front.index'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('front.index'));
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('front.auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['required', 'string', 'max:255'], // Make phone_number required
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number, // Save phone_number
        ]);

        Auth::login($user);

        return redirect(route('front.index'));
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if a user is already logged in
            if (Auth::check()) {
                $user = Auth::user();

                // Check if this Google account is already linked to another user
                $existingGoogleUser = User::where('google_id', $googleUser->id)
                                         ->where('id', '!=', $user->id)
                                         ->first();

                if ($existingGoogleUser) {
                    // Google account already linked to another user
                    Log::warning('Attempted to link Google account already linked to another user. Google ID: ' . $googleUser->id . ', Current User ID: ' . $user->id . ', Existing Linked User ID: ' . $existingGoogleUser->id);
                    return redirect()->route('front.customer.dashboard')->withErrors(['google_linking' => 'This Google account is already linked to another user.']);
                }

                // Check if the Google account's email exists for a different user
                $existingUserWithGoogleEmail = User::where('email', $googleUser->email)
                                                  ->where('id', '!=', $user->id)
                                                  ->first();

                if ($existingUserWithGoogleEmail) {
                    // An account with this email already exists
                     Log::warning('Attempted to link Google account with email already existing for another user. Google Email: ' . $googleUser->email . ', Current User ID: ' . $user->id . ', Existing User ID: ' . $existingUserWithGoogleEmail->id);
                    return redirect()->route('front.customer.dashboard')->withErrors(['google_linking' => 'An account with this Google email address already exists.']);
                }


                // Link the Google account to the currently authenticated user
                $user->google_id = $googleUser->id;
                $user->google_token = $googleUser->token;
                // Do NOT update the user's email, name, or phone number here
                $user->save();

                Log::info('Google account linked successfully for user ID: ' . $user->id . ' with email: ' . $user->email . ' and Google ID: ' . $user->google_id);

                return redirect()->route('front.customer.dashboard')->with('success', 'Google account linked successfully.');
            }

            // If not logged in, proceed with existing login/registration logic
            $findUser = User::where('google_id', $googleUser->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                // Check if phone number is missing, redirect to edit profile
                if (empty($findUser->phone_number)) {
                    return redirect()->route('front.customer.editProfile');
                }
                return redirect()->intended(route('front.index')); // Redirect to intended page or home
            } else {
                // Check if a user with the same email already exists
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // If user exists with the same email, link the Google account
                    $existingUser->google_id = $googleUser->id;
                    $existingUser->google_token = $googleUser->token;
                    $existingUser->save();

                    Auth::login($existingUser);
                     // Check if phone number is missing, redirect to edit profile
                    if (empty($existingUser->phone_number)) {
                        return redirect()->route('front.customer.editProfile');
                    }
                    return redirect()->intended(route('front.index'));
                } else {
                    // Create a new user
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                        'password' => Hash::make(Str::random(16)), // Generate a random password
                        'phone_number' => null, // Set phone_number to null initially for Google users
                    ]);

                    Auth::login($newUser);
                    // Redirect new Google users to edit profile to add phone number
                    return redirect()->route('front.customer.editProfile');
                }
            }
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            Log::error('Google login/linking failed: ' . $e->getMessage());
            return redirect(route('front.index'))->withErrors(['google_login' => 'Unable to process Google login or linking. Please try again.']);
        }
    }
}
