<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        // SECURITY: Redirect authenticated users away from login page
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     * SECURITY FIX: Added rate limiting and account lockout protection
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        // SECURITY: Account lockout - check if account is temporarily locked
        $lockoutKey = 'login_attempts:' . $request->ip() . ':' . $credentials['email'];
        $lockoutAttempts = Cache::get($lockoutKey, 0);
        
        if ($lockoutAttempts >= 5) {
            \Illuminate\Support\Facades\Log::warning('Account locked due to failed attempts', [
                'email' => $credentials['email'], 
                'ip' => $request->ip()
            ]);
            
            return back()->withErrors([
                'email' => 'Too many failed attempts. Please try again in 15 minutes.',
            ])->onlyInput('email');
        }

        // SECURITY: Check if user exists and is an admin before attempting login
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || !$user->isAdmin()) {
            // Increment failed attempts
            Cache::put($lockoutKey, $lockoutAttempts + 1, now()->addMinutes(15));
            
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Clear failed attempts on successful login
            Cache::forget($lockoutKey);
            
            $request->session()->regenerate();
            
            // SECURITY: Log successful authentication
            \Illuminate\Support\Facades\Log::info('Admin logged in', ['email' => $user->email, 'ip' => $request->ip()]);
            
            return redirect()->intended('/admin/dashboard');
        }

        // Increment failed attempts
        Cache::put($lockoutKey, $lockoutAttempts + 1, now()->addMinutes(15));
        
        // SECURITY: Log failed authentication attempt
        \Illuminate\Support\Facades\Log::warning('Failed login attempt', [
            'email' => $credentials['email'], 
            'ip' => $request->ip(),
            'attempt' => $lockoutAttempts + 1
        ]);

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     * SECURITY FIX: Disable public registration - only existing admins can create new admins
     */
    public function showRegister()
    {
        // SECURITY: Completely disable public registration
        // If you need to create admin users, use artisan command or database seeder
        abort(403, 'Registration is disabled. Please contact the administrator.');
    }

    /**
     * Handle registration request
     * SECURITY FIX: Completely disabled to prevent unauthorized admin creation
     */
    public function register(Request $request)
    {
        // SECURITY: Completely disable public registration
        abort(403, 'Registration is disabled. Please contact the administrator.');
    }

    /**
     * SECURITY: Admin-only method to create new admin users
     * This should be called from an admin-only interface
     */
    public function createAdmin(Request $request)
    {
        // Ensure only authenticated admins can create new admins
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                // Password history check - prevent reuse of last 5 passwords
                function ($attribute, $value, $fail) use ($request) {
                    // Check if password contains common weak patterns
                    $weakPatterns = ['password', '123456', 'qwerty', 'admin', 'letmein'];
                    foreach ($weakPatterns as $pattern) {
                        if (stripos($value, $pattern) !== false) {
                            $fail('Password contains common weak patterns.');
                            return;
                        }
                    }
                    // Check if password contains user's name or email
                    if (stripos($value, $request->input('name')) !== false || 
                        stripos($value, explode('@', $request->input('email'))[0]) !== false) {
                        $fail('Password cannot contain your name or email.');
                    }
                },
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        \Illuminate\Support\Facades\Log::info('New admin created', ['created_by' => Auth::user()->email, 'new_admin' => $user->email]);

        return redirect()->route('admin.dashboard')->with('success', 'New admin user created successfully.');
    }

    /**
     * Handle logout request
     * SECURITY FIX: Proper session invalidation
     */
    public function logout(Request $request)
    {
        // Log the logout action
        if (Auth::check()) {
            \Illuminate\Support\Facades\Log::info('Admin logged out', ['email' => Auth::user()->email, 'ip' => $request->ip()]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
