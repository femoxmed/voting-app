<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Shareholder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShareholderSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        
        $shareholderData = [
            ['name' => 'John Smith', 'email' => 'john.smith@example.com'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@example.com'],
            ['name' => 'Michael Brown', 'email' => 'michael.brown@example.com'],
            ['name' => 'Emily Davis', 'email' => 'emily.davis@example.com'],
            ['name' => 'David Wilson', 'email' => 'david.wilson@example.com'],
            ['name' => 'Lisa Anderson', 'email' => 'lisa.anderson@example.com'],
            ['name' => 'Robert Taylor', 'email' => 'robert.taylor@example.com'],
            ['name' => 'Jennifer Martinez', 'email' => 'jennifer.martinez@example.com'],
        ];

        foreach ($companies as $company) {
            // Create 3-4 shareholders per company
            $shareholdersForCompany = collect($shareholderData)->random(rand(3, 4));
            
            foreach ($shareholdersForCompany as $index => $shareholderInfo) {
                $user = User::updateOrCreate(
                    ['email' => $shareholderInfo['email']],
                    [
                        'name' => $shareholderInfo['name'],
                        'password' => Hash::make('password'),
                        'role' => 'shareholder',
                        'email_verified_at' => now(),
                    ]
                );

                Shareholder::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'company_id' => $company->id,
                    ],
                    [
                        'shares_owned' => rand(100, 5000),
                        'shareholder_id' => strtoupper($company->code) . '-SH-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    ]
                );
            }
        }
    }
}
