<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>{{ __('messages.Order Success') }}</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="{{ asset('assets/admin/fonts/SansPro/SansPro.min.css') }}">
    @if (App::getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css') }}">
    @endif
    
<style>
    body {
        background: #f8fafc;
    }
    
    .success-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 3rem 0;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }
    
    .success-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .order-details {
        border-top: 1px solid #e5e7eb;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
    }
    
    .orders-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background: #f9fafb;
        border-top: none;
        font-weight: 600;
        color: #374151;
        padding: 1rem;
    }
    
    .table td {
        padding: 1rem;
        vertical-align: middle;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-confirmed {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-delivered {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .action-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .btn-view {
        background: #667eea;
        color: white;
        border: none;
    }
    
    .btn-view:hover {
        background: #5a67d8;
        color: white;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #d1d5db;
    }
    
    @media (max-width: 768px) {
        .success-header {
            padding: 2rem 0;
        }
        
        .success-icon {
            width: 60px;
            height: 60px;
            font-size: 2rem;
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
    }
</style>
</head>
<body>

<!-- Success Header -->
<div class="success-header">
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="mb-2">{{ __('messages.Order Placed Successfully!') }}</h1>
        <p class="lead">{{ __('messages.Thank you for your order. We will contact you soon.') }}</p>
    </div>
</div>

<div class="container">
    <!-- Order Success Card -->
    <div class="success-card">
        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-3">{{ __('messages.Order Information') }}</h4>
                <ul class="list-unstyled">
                    <li><strong>{{ __('messages.Order Number') }}:</strong> #{{ $order->number }}</li>
                    <li><strong>{{ __('messages.Customer Name') }}:</strong> {{ $order->user->name }}</li>
                    <li><strong>{{ __('messages.Phone') }}:</strong> {{ $order->user->phone }}</li>
                    <li><strong>{{ __('messages.Order Date') }}:</strong> {{ \Carbon\Carbon::parse($order->date)->format('M d, Y - h:i A') }}</li>
                    <li><strong>{{ __('messages.Delivery') }}:</strong> {{ $order->delivery->place ?? 'N/A' }}</li>
                    <li><strong>{{ __('messages.Payment Type') }}:</strong> {{ ucfirst($order->payment_type) }}</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h4 class="mb-3">{{ __('messages.Order Summary') }}</h4>
                <div class="order-details">
                    <div class="order-item">
                        <span>{{ __('messages.Subtotal') }}:</span>
                        <span>JD {{ number_format($order->total_prices, 2) }}</span>
                    </div>
                    <div class="order-item text-success">
                        <span>{{ __('messages.Discount') }}:</span>
                        <span>-JD {{ number_format($order->total_discounts, 2) }}</span>
                    </div>
                    <div class="order-item">
                        <span>{{ __('messages.Delivery Fee') }}:</span>
                        <span>JD {{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                    <div class="order-item border-top pt-3" style="font-weight: bold; font-size: 1.125rem;">
                        <span>{{ __('messages.Total') }}:</span>
                        <span>JD {{ number_format($order->total_prices + $order->delivery_fee, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="mt-4">
            <h4 class="mb-3">{{ __('messages.Order Items') }}</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.Product') }}</th>
                            <th>{{ __('messages.Quantity') }}</th>
                            <th>{{ __('messages.Unit Price') }}</th>
                            <th>{{ __('messages.Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderProducts as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/admin/uploads/' . $item->product->productImages->first()->photo) }}" 
                                         alt="{{ $item->product->name_en ?? $item->product->name_ar }}" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 1rem;">
                                    <span>{{ $item->product->name_en ?? $item->product->name_ar }}</span>
                                </div>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                @if($item->discount_value > 0)
                                    <span class="text-decoration-line-through text-muted me-2">JD {{ number_format($item->unit_price, 2) }}</span>
                                    <span class="text-danger">JD {{ number_format($item->unit_price - ($item->discount_value / $item->quantity), 2) }}</span>
                                @else
                                    JD {{ number_format($item->unit_price, 2) }}
                                @endif
                            </td>
                            <td>JD {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>{{ __('messages.Create Another Order') }}
            </a>
        </div>
    </div>
    
    <!-- Order History Section -->
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-history me-2 text-primary"></i>{{ __('messages.Your Order History') }}
        </div>
        <div class="customer-info">
            <i class="fas fa-user"></i> {{ $order->user->name }}
            <i class="fas fa-phone ms-3"></i> {{ $order->user->phone }}
        </div>
    </div>
    
    @if($userOrders->count() > 0)
    <div class="orders-table">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('messages.Order') }} #</th>
                    <th>{{ __('messages.Date') }}</th>
                    <th>{{ __('messages.Items') }}</th>
                    <th>{{ __('messages.Total') }}</th>
                    <th>{{ __('messages.Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userOrders as $userOrder)
                <tr>
                    <td>
                        <span class="fw-semibold">#{{ $userOrder->number }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($userOrder->date)->format('M d, Y') }}</td>
                    <td>{{ $userOrder->orderProducts->count() }} {{ Str::plural('item', $userOrder->orderProducts->count()) }}</td>
                    <td>JD {{ number_format($userOrder->total_prices + $userOrder->delivery_fee, 2) }}</td>
                    <td>
                        @switch($userOrder->order_status)
                            @case(1)
                                <span class="status-badge status-pending">{{ __('messages.Pending') }}</span>
                                @break
                            @case(2)
                                <span class="status-badge status-confirmed">{{ __('messages.Confirmed') }}</span>
                                @break
                            @case(3)
                                <span class="status-badge status-delivered">{{ __('messages.Delivered') }}</span>
                                @break
                            @case(0)
                                <span class="status-badge status-cancelled">{{ __('messages.Cancelled') }}</span>
                                @break
                            @default
                                <span class="status-badge status-pending">{{ __('messages.Pending') }}</span>
                        @endswitch
                    </td>
                   
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($userOrders->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $userOrders->links() }}
    </div>
    @endif
    
    @else
    <div class="empty-state">
        <i class="fas fa-shopping-bag"></i>
        <p>{{ __('messages.You have no previous orders') }}</p>
    </div>
    @endif
    
    <div class="text-center my-5">
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            <i class="fas fa-home me-2"></i>{{ __('messages.Back to Homepage') }}
        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>