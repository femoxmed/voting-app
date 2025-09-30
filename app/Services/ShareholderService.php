<?php

namespace App\Services;

use App\Models\Shareholder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShareholderService
{
    public function createShareholder(array $data): Shareholder
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make(Str::random(12)),
                'role' => 'shareholder',
            ]);

            return Shareholder::create([
                'user_id' => $user->id,
                'company_id' => $data['company_id'],
                'shares_owned' => $data['shares_owned'],
                'shareholder_id' => $data['shareholder_id'],
            ]);
        });
    }

    public function updateSharesOwned(Shareholder $shareholder, int $sharesOwned): void
    {
        $shareholder->update(['shares_owned' => $sharesOwned]);
    }
}