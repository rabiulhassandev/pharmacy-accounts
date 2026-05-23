<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $pharmacyName = Setting::get('pharmacy_name', 'Mokka Pharmachy');
        $currency = Setting::get('currency_symbol', '৳');

        return view('settings.edit', compact('pharmacyName', 'currency'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pharmacy_name' => ['required', 'string', 'max:100'],
            'currency_symbol' => ['required', 'string', 'max:10'],
        ]);

        Setting::set('pharmacy_name', $validated['pharmacy_name']);
        Setting::set('currency_symbol', $validated['currency_symbol']);

        return back()->with('success', 'Settings updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
