@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-coins"></i> {{ $title }}
        </h2>
        <div>
            <a href="{{ route('reports.clients') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
            <button onclick="window.print()" class="btn btn-outline-primary ml-2">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Average Client Value
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($averageLTV, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Value of All Clients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @php
                                    $totalValue = App\Models\Client::withSum('payments', 'amount')->get()->sum('payments_sum_amount');
                                @endphp
                                {{ number_format($totalValue, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Client Lifetime Value Ranking</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Rank</th>
                            <th>Client Name</th>
                            <th>Company</th>
                            <th>Total Value</th>
                            <th>Contracts</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->company_name ?? 'N/A' }}</td>
                            <td>{{ number_format($client->payments_sum_amount, 2) }}</td>
                            <td>{{ $client->contracts->count() ?? 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($clients->hasPages())
            <div class="mt-3">
                {{ $clients->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection