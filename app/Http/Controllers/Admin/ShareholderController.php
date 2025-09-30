<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shareholder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ShareholderWelcomeNotification;

class ShareholderController extends Controller
{
    public function index()
    {
        $shareholders = Shareholder::with(['user', 'company'])->latest()->paginate(15);
        return view('admin.shareholders.index', compact('shareholders'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('admin.shareholders.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'company_id' => 'required|exists:companies,id',
            'shares_owned' => 'required|integer|min:1',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt('password'), // Default password
            'role' => 'shareholder',
        ]);

         // Send welcome notification
        $user->notify(new ShareholderWelcomeNotification());

        // Generate shareholder ID
        $company = Company::find($validated['company_id']);
        $shareholderCount = Shareholder::where('company_id', $validated['company_id'])->count() + 1;
        $shareholderId = strtoupper($company->code) . '-SH-' . str_pad($shareholderCount, 3, '0', STR_PAD_LEFT);

        // Create shareholder
        Shareholder::create([
            'user_id' => $user->id,
            'company_id' => $validated['company_id'],
            'shares_owned' => $validated['shares_owned'],
            'shareholder_id' => $shareholderId,
        ]);

        return redirect()->route('admin.shareholders.index')
            ->with('success', 'Shareholder created successfully.');
    }

    public function show(Shareholder $shareholder)
    {
        $shareholder->load(['user', 'company']);
        return view('admin.shareholders.show', compact('shareholder'));
    }

    public function edit(Shareholder $shareholder)
    {
        $companies = Company::all();
        $shareholder->load(['user', 'company']);
        return view('admin.shareholders.edit', compact('shareholder', 'companies'));
    }

    public function update(Request $request, Shareholder $shareholder)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $shareholder->user_id,
            'company_id' => 'required|exists:companies,id',
            'shares_owned' => 'required|integer|min:1',
        ]);

        // Update user
        $shareholder->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update shareholder
        $shareholder->update([
            'company_id' => $validated['company_id'],
            'shares_owned' => $validated['shares_owned'],
        ]);

        return redirect()->route('admin.shareholders.show', $shareholder)
            ->with('success', 'Shareholder updated successfully.');
    }

    public function destroy(Shareholder $shareholder)
    {
        $shareholder->user->delete();
        $shareholder->delete();

        return redirect()->route('admin.shareholders.index')
            ->with('success', 'Shareholder deleted successfully.');
    }
}
