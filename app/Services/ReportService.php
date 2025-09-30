<?php

namespace App\Services;

use App\Models\Agm;
use Illuminate\Http\Response;

class ReportService
{
    public function generateAgmReport(Agm $agm): array
    {
        $agm->load(['votingItems.votes.shareholder.user', 'company']);

        $totalShareholders = $agm->company->shareholders()->count();
        $totalShares = $agm->company->shareholders()->sum('shares_owned');

        $votingResults = [];
        foreach ($agm->votingItems as $item) {
            $votingResults[] = [
                'item' => $item,
                'total_votes' => $item->total_votes,
                'breakdown' => $item->vote_breakdown,
                'participation_rate' => $totalShares > 0 ? ($item->total_votes / $totalShares) * 100 : 0,
                'voter_count' => $item->votes()->distinct('shareholder_id')->count(),
            ];
        }

        return [
            'agm' => $agm,
            'total_shareholders' => $totalShareholders,
            'total_shares' => $totalShares,
            'voting_results' => $votingResults,
            'overall_participation' => $this->calculateOverallParticipation($agm),
        ];
    }

    public function exportAgmReport(Agm $agm): Response
    {
        $report = $this->generateAgmReport($agm);

        $csv = $this->generateCsvReport($report);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="agm-report-' . $agm->id . '.csv"');
    }

    private function calculateOverallParticipation(Agm $agm): float
    {
        $totalShareholders = $agm->company->shareholders()->count();

        if ($totalShareholders === 0) {
            return 0;
        }

        $participatedShareholders = $agm->votingItems()
            ->join('votes', 'voting_items.id', '=', 'votes.voting_item_id')
            ->distinct('votes.shareholder_id')
            ->count();

        return ($participatedShareholders / $totalShareholders) * 100;
    }

    private function generateCsvReport(array $report): string
    {
        $output = fopen('php://temp', 'r+');

        // Headers
        fputcsv($output, ['AGM Report: ' . $report['agm']->title]);
        fputcsv($output, ['Company: ' . $report['agm']->company->name]);
        fputcsv($output, ['Date: ' . $report['agm']->start_date->format('Y-m-d H:i')]);
        fputcsv($output, []);

        // Summary
        fputcsv($output, ['Summary']);
        fputcsv($output, ['Total Shareholders', $report['total_shareholders']]);
        fputcsv($output, ['Total Shares', $report['total_shares']]);
        fputcsv($output, ['Overall Participation Rate', number_format($report['overall_participation'], 2) . '%']);
        fputcsv($output, []);

        // Voting Results
        fputcsv($output, ['Voting Results']);
        fputcsv($output, ['Item', 'Total Votes', 'Participation Rate', 'Results']);

        foreach ($report['voting_results'] as $result) {
            $breakdownArr = (array) ($result['breakdown'] ?? []);
            $breakdown = implode('; ', array_map(
                fn($option, $votes) => "$option: $votes",
                array_keys($breakdownArr),
                $breakdownArr
            ));

            fputcsv($output, [
                $result['item']->title,
                $result['total_votes'],
                number_format($result['participation_rate'], 2) . '%',
                $breakdown
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
