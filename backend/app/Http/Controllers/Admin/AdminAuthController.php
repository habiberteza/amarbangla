<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isSuperAdmin()) return redirect()->route('admin.dashboard');
            if ($user->role === 'agent') return redirect()->route('agent.dashboard');
            if ($user->role === 'vendor') return redirect()->route('vendor.dashboard');
            if ($user->role === 'seller') return redirect()->route('seller.dashboard');
            if ($user->role === 'delivery_boy') return redirect()->route('delivery.dashboard');
            return redirect()->route('home');
        }
        return view('admin.login');
    }

    public function showLoginForm()
    {
        return $this->showLogin();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = trim($request->email);
        $password = $request->password;

        $digits = preg_replace('/[^0-9]/', '', $login);
        $standardPhone = strlen($digits) >= 10 ? ('0' . substr($digits, -10)) : null;
        $intMobile = strlen($digits) >= 10 ? (int) substr($digits, -10) : null;

        $user = \App\Models\User::where(function ($q) use ($login, $standardPhone, $intMobile) {
            $q->where('email', $login)
              ->orWhere('phone', $login);
            if ($standardPhone) $q->orWhere('phone', $standardPhone);
            if ($intMobile) $q->orWhere('mobile', $intMobile);
        })->first();

        if ($user) {
            $validPassword = \Illuminate\Support\Facades\Hash::check($password, $user->password)
                || (sha1($password) === $user->password)
                || (md5($password) === $user->password)
                || (!empty($user->pass) && $password === $user->pass);

            if ($validPassword) {
                if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                    $user->password = \Illuminate\Support\Facades\Hash::make($password);
                    $user->save();
                }

                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();

                if ($user->isSuperAdmin()) {
                    return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome to Super Admin Panel');
                }
                if ($user->role === 'agent') {
                    return redirect()->intended(route('agent.dashboard'))->with('success', 'Welcome to Agent Partner Panel');
                }
                if ($user->role === 'vendor') {
                    return redirect()->intended(route('vendor.dashboard'))->with('success', 'Welcome to Shop Merchant Panel');
                }
                if ($user->role === 'seller') {
                    return redirect()->intended(route('seller.dashboard'))->with('success', 'Welcome to Seller & Affiliate Panel');
                }
                if ($user->role === 'delivery_boy') {
                    return redirect()->intended(route('delivery.dashboard'))->with('success', 'Welcome to Delivery Partner Panel');
                }

                return redirect()->intended('/')->with('success', 'Welcome to Amar Bangla');
            }
        }

        return back()->withErrors(['email' => 'Invalid email/phone or password provided.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('info', 'Logged out successfully');
    }
}
