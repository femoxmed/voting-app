<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'TechCorp Solutions Ltd',
                'code' => 'TC001',
                'description' => 'Leading technology solutions provider specializing in enterprise software development.',
            ],
            [
                'name' => 'Global Industries Inc',
                'code' => 'GI002', 
                'description' => 'Multinational industrial conglomerate with operations across manufacturing and logistics.',
            ],
            [
                'name' => 'Innovation Holdings Plc',
                'code' => 'IH003',
                'description' => 'Investment holding company focused on emerging technologies and startups.',
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
