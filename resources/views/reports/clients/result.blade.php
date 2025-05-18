@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-chart-bar"></i> {{ $title }}
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

    @if($report_type == 'acquisition')
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    New Clients
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($newClients) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Clients
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($totalClients) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Growth Rate
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($clientGrowthRate, 2) }}%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Client Acquisition Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Client Name</th>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Signup Date</th>
                                <th>Days Since</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->company_name ?? 'N/A' }}</td>
                                <td>
                                    @if($client->phone)
                                    <div><i class="fas fa-phone"></i> {{ $client->phone }}</div>
                                    @endif
                                    @if($client->email)
                                    <div><i class="fas fa-envelope"></i> {{ $client->email }}</div>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($client->created_at)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($client->created_at)->diffInDays() }} days</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No clients found for this timeframe</td>
                            </tr>
                            @endforelse
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

    
    @endif
</div>
@endsection