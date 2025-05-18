<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;


use App\Models\Client;
use Carbon\Carbon;

class ClientReportController extends Controller
{
    public function index()
    {
        return view('reports.clients.index');
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'timeframe' => 'required|in:today,this_week,this_month,this_year,all_time',
            'report_type' => 'required|in:acquisition,demographics,activity'
        ]);

        $reportData = [];

        switch ($validated['report_type']) {
            case 'acquisition':
                $reportData = $this->generateAcquisitionReport($validated['timeframe']);
                break;
        }

        return view('reports.clients.result', array_merge($reportData, [
            'timeframe' => $validated['timeframe'],
            'report_type' => $validated['report_type']
        ]));
    }

    protected function generateAcquisitionReport($timeframe)
    {
        $query = Client::query();
        $title = "Client Acquisition Report";

        switch ($timeframe) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                $title .= " - Today";
                break;
                
            case 'this_week':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                $title .= " - This Week";
                break;
                
            case 'this_month':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ]);
                $title .= " - This Month";
                break;
                
            case 'this_year':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                ]);
                $title .= " - This Year";
                break;
        }

        $newClients = $query->count();
        $totalClients = Client::count();
        $clientGrowthRate = $totalClients > 0 ? ($newClients / $totalClients) * 100 : 0;

        return [
            'title' => $title,
            'newClients' => $newClients,
            'totalClients' => $totalClients,
            'clientGrowthRate' => $clientGrowthRate,
            'clients' => $query->orderBy('created_at', 'desc')->paginate(15)
        ];
    }


    

    public function clientLifetimeValue()
    {
        $clients = Client::withSum('payments', 'amount')
            ->orderBy('payments_sum_amount', 'desc')
            ->paginate(15);

        $averageLTV = Client::withSum('payments', 'amount')
            ->having('payments_sum_amount', '>', 0)
            ->avg('payments_sum_amount');

        return view('reports.clients.lifetime-value', [
            'title' => 'Client Lifetime Value Report',
            'clients' => $clients,
            'averageLTV' => $averageLTV
        ]);
    }
}
