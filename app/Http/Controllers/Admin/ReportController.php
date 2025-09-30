<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agm;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function index()
    {
        $agms = Agm::with('company')->latest()->get();
        return view('admin.reports.index', compact('agms'));
    }

    public function agmReport(Agm $agm)
    {
        $report = $this->reportService->generateAgmReport($agm);
        return view('admin.reports.agm', compact('agm', 'report'));
    }

    public function exportReport(Agm $agm)
    {
        return $this->reportService->exportAgmReport($agm);
    }
}
