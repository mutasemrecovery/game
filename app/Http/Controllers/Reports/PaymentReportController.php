<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentReportController extends Controller
{
    public function index()
    {
        return view('reports.payments.index');
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'timeframe' => 'required|in:today,this_week,this_month,next_3_months'
        ]);

        $query = Payment::with(['contract.client'])
            ->where('status', 'pending')
            ->orderBy('due_date');

        switch ($request->timeframe) {
            case 'today':
                $query->whereDate('due_date', Carbon::today());
                $title = "Today's Due Payments";
                break;
                
            case 'this_week':
                $query->whereBetween('due_date', [
                    Carbon::today(),
                    Carbon::today()->endOfWeek()
                ]);
                $title = "This Week's Due Payments";
                break;
                
            case 'this_month':
                $query->whereBetween('due_date', [
                    Carbon::today(),
                    Carbon::today()->endOfMonth()
                ]);
                $title = "This Month's Due Payments";
                break;
                
            case 'next_3_months':
                $query->whereBetween('due_date', [
                    Carbon::today(),
                    Carbon::today()->addMonths(3)
                ]);
                $title = "Payments Due in Next 3 Months";
                break;
        }

        $payments = $query->get();
        $timeframe = $request->timeframe;

        return view('reports.payments.result', compact('payments', 'title', 'timeframe'));
    }

    public function overduePayments(Request $request)
    {
        $query = Payment::with(['contract.client'])
            ->where('status', 'pending')
            ->whereDate('due_date', '<', Carbon::today())
            ->orderBy('due_date');
    
        // Optional filters
        if ($request->has('client_id')) {
            $query->whereHas('contract', function($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }
    
        if ($request->has('days_overdue')) {
            $days = (int)$request->days_overdue;
            $query->whereDate('due_date', '<=', Carbon::today()->subDays($days));
        }
    
        $payments = $query->paginate(25);
        $clients = Client::orderBy('name')->get();
    
        return view('reports.payments.overdue', [
            'payments' => $payments,
            'clients' => $clients,
            'title' => 'Overdue Payments Report',
            'totalOverdue' => $query->sum('amount')
        ]);
    }
}
