<?php

namespace Database\Seeders;

use App\Models\Agm;
use App\Models\Company;
use App\Models\VotingItem;
use Illuminate\Database\Seeder;

class AgmSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            // Create upcoming AGM
            $agm = Agm::create([
                'company_id' => $company->id,
                'title' => $company->name . ' Annual General Meeting 2024',
                'description' => 'Annual General Meeting for the year 2024',
                'start_date' => now()->addDays(30),
                'status' => 'active',
            ]);

            $this->createVotingItems($agm);

            // Create past AGM
            $pastAgm = Agm::create([
                'company_id' => $company->id,
                'title' => $company->name . ' Annual General Meeting 2023',
                'description' => 'Annual General Meeting for the year 2023',
                'start_date' => now()->subDays(60),
                'status' => 'completed',
            ]);

            $this->createVotingItems($pastAgm);
        }
    }

    private function createVotingItems(Agm $agm): void
    {
        $votingItems = [
            [
                'title' => 'Approval of Annual Financial Statements',
                'description' => 'To approve the audited financial statements for the financial year ended December 31, 2023.',
                'type' => 'yes_no',
            ],
            [
                'title' => 'Re-election of Directors',
                'description' => 'To re-elect the following directors who retire by rotation: John Doe, Jane Smith.',
                'type' => 'yes_no',
            ],
            [
                'title' => 'Appointment of Auditors',
                'description' => 'To appoint PwC as auditors for the ensuing year and authorize directors to fix their remuneration.',
                'type' => 'yes_no',
            ],
            [
                'title' => 'Declaration of Final Dividend',
                'description' => 'To declare a final dividend of $0.50 per ordinary share for the financial year ended December 31, 2023.',
                'type' => 'yes_no',
            ],
            [
                'title' => 'Amendment to Articles of Association',
                'description' => 'To approve amendments to the Company\'s Articles of Association to align with new regulatory requirements.',
                'type' => 'yes_no',
            ],
            [
                'title' => 'Board Member Selection',
                'description' => 'Select the preferred candidate for the new board position from the following nominees.',
                'type' => 'multiple_choice',
            ],
        ];

        foreach ($votingItems as $item) {
            $votingData = [
                'agm_id' => $agm->id,
                'title' => $item['title'],
                'description' => $item['description'],
                'type' => $item['type'],
            ];

            // Add options for multiple choice items
            if ($item['type'] === 'multiple_choice') {
                $votingData['options'] = json_encode([
                    'Candidate A - John Smith',
                    'Candidate B - Sarah Johnson', 
                    'Candidate C - Michael Brown'
                ]);
            }

            VotingItem::create($votingData);
        }
    }
}
