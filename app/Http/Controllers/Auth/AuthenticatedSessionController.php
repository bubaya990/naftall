<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        logger('Attempting to authenticate...');
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo());
    }

    public function destroy(Request $request): RedirectResponse
    {
        logger('Logout method called');

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function redirectTo(): string
    {
        return '/superadmin/dashboard';
    }
}