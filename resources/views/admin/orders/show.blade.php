@extends('layouts.admin')

@section('title')
    {{ __('messages.invoice') }} #{{ $order->number }}
@endsection

@section('css')
<style>
    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        
        .d-print-none {
            display: none !important;
        }
        
        body {
            font-size: 11px !important;
            line-height: 1.3 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        
        .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            max-width: none !important;
        }
        
        .invoice-container {
            margin: 0 !important;
            padding: 10mm !important;
            max-width: none !important;
            width: 100% !important;
            box-shadow: none !important;
        }
        
        .invoice-header {
            margin-bottom: 15px !important;
            padding-bottom: 15px !important;
        }
        
        .company-name {
            font-size: 22px !important;
            margin-bottom: 8px !important;
        }
        
        .invoice-title {
            font-size: 28px !important;
            margin-bottom: 8px !important;
        }
        
        .invoice-details {
            margin-bottom: 15px !important;
        }
        
        .bill-to h4, .delivery-info h4 {
            font-size: 14px !important;
            margin-bottom: 8px !important;
        }
        
        .items-table {
            margin-bottom: 15px !important;
            font-size: 10px !important;
        }
        
        .items-table th {
            padding: 8px 4px !important;
            font-size: 10px !important;
        }
        
        .items-table td {
            padding: 8px 4px !important;
            font-size: 10px !important;
        }
        
        .invoice-summary {
            margin-bottom: 15px !important;
        }
        
        .total-table {
            font-size: 11px !important;
        }
        
        .total-table td {
            padding: 6px 8px !important;
        }
        
        .invoice-footer {
            margin-top: 15px !important;
            padding-top: 10px !important;
        }
        
        .thank-you {
            font-size: 12px !important;
        }
        
        /* Prevent page breaks inside important elements */
        .invoice-header,
        .invoice-details,
        .items-table,
        .invoice-summary {
            page-break-inside: avoid;
        }
        
        /* Ensure table rows don't break */
        .items-table tr {
            page-break-inside: avoid;
        }
    }

    @page {
        size: A4;
        margin: 12mm 10mm;
    }

    .invoice-container {
        max-width: 190mm;
        margin: 0 auto;
        padding: 15px;
        background: white;
        font-family: 'Arial', sans-serif;
        color: #333;
        box-sizing: border-box;
    }

    .invoice-header {
        border-bottom: 2px solid #007bff;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .company-name {
        font-size: 28px;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 10px;
    }

    .company-address {
        color: #666;
        line-height: 1.6;
    }

    .invoice-title {
        font-size: 36px;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 10px;
    }

    .invoice-meta p {
        margin-bottom: 5px;
        font-size: 14px;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-1 { background-color: #ffc107; color: #856404; }
    .status-2 { background-color: #17a2b8; color: white; }
    .status-3 { background-color: #dc3545; color: white; }
    .status-4 { background-color: #6c757d; color: white; }
    .status-5 { background-color: #fd7e14; color: white; }
    .status-6 { background-color: #28a745; color: white; }

    .invoice-details {
        margin-bottom: 30px;
    }

    .bill-to h4, .delivery-info h4 {
        color: #007bff;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .customer-info p, .delivery-info p {
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .payment-status {
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }

    .payment-1 { background-color: #28a745; color: white; }
    .payment-2 { background-color: #dc3545; color: white; }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
        overflow: hidden;
        border-radius: 6px;
    }

    .items-table th {
        background-color: #007bff;
        color: white;
        padding: 12px 8px;
        text-align: center;
        font-weight: bold;
        border-right: 1px solid rgba(255,255,255,0.2);
        white-space: nowrap;
    }

    .items-table th:last-child {
        border-right: none;
    }

    .items-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        vertical-align: top;
        text-align: center;
    }

    .items-table td:last-child {
        border-right: none;
    }

    .items-table td:nth-child(2) {
        text-align: left;
    }

    .items-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .items-table tbody tr:last-child td {
        border-bottom: none;
    }

    .product-info strong {
        color: #333;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .invoice-summary {
        margin-bottom: 30px;
    }

    .payment-info h5 {
        color: #007bff;
        margin-bottom: 15px;
    }

    .total-table {
        width: 100%;
        margin-left: auto;
    }

    .total-table td {
        padding: 8px 12px;
        text-align: right;
        border-bottom: 1px solid #dee2e6;
    }

    .total-table td:first-child {
        text-align: left;
        width: 60%;
    }

    .discount {
        color: #28a745;
        font-weight: bold;
    }

    .total-row {
        border-top: 2px solid #007bff;
        font-size: 16px;
    }

    .total-row td {
        padding: 12px;
        background-color: #f8f9fa;
    }

    .invoice-footer {
        border-top: 1px solid #dee2e6;
        padding-top: 20px;
        margin-top: 30px;
    }

    .terms h5 {
        color: #007bff;
        margin-bottom: 10px;
    }

    .terms p {
        font-size: 12px;
        color: #666;
        line-height: 1.5;
    }

    .thank-you {
        text-align: center;
        margin-top: 20px;
        font-size: 16px;
        color: #007bff;
    }
</style>
   
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3 d-print-none">
        <div class="col-12">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> {{ __('messages.print') }}
            </button>
        </div>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="row">
                <div class="col-6">
                    <div class="company-info">
                        <h2 class="company-name">{{ config('app.name', 'Your Company') }}</h2>
                        <p class="company-address">
                            Your Company Address<br>
                           00775504609<br>
                        </p>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <h1 class="invoice-title">{{ __('messages.invoice') }}</h1>
                    <div class="invoice-meta">
                        <p><strong>{{ __('messages.invoice_number') }}:</strong> #{{ $order->number }}</p>
                        <p><strong>{{ __('messages.date') }}:</strong> {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</p>
                      
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-details">
            <div class="row">
                <div class="col-6">
                    <div class="bill-to">
                        <h4>{{ __('messages.bill_to') }}:</h4>
                        <div class="customer-info">
                            <p><strong>{{ $order->user->name }}</strong></p>
                            <p>{{ $order->user->email ?? '' }}</p>
                            <p>{{ $order->user->phone ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="delivery-info">
                        <h4>{{ __('messages.delivery_information') }}:</h4>
                        <p><strong>{{ __('messages.delivery_place') }}:</strong> {{ $order->delivery->place }} <br>{{ $order->address }}</p>
                        <p><strong>{{ __('messages.payment_type') }}:</strong> {{ ucfirst($order->payment_type) }}</p>
                        <p><strong>{{ __('messages.payment_status') }}:</strong> 
                            <span class="payment-status payment-{{ $order->payment_status }}">
                                {{ $order->payment_status == 1 ? __('messages.paid') : __('messages.unpaid') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-items">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.product') }}</th>
                        <th>{{ __('messages.quantity') }}</th>
                        <th>{{ __('messages.unit_price') }}</th>
                        <th>{{ __('messages.discount') }}</th>
                        <th>{{ __('messages.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderProducts as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="product-info">
                                <strong>{{ $item->product->name_ar }}</strong>
                                @if($item->product->number)
                                    <br><small class="text-muted">{{ $item->product->number}}</small>
                                @endif
                            </div>
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>JD{{ number_format($item->unit_price, 2) }}</td>
                        <td>
                            @if($item->discount_percentage > 0)
                                {{ $item->discount_percentage }}%<br>
                                <small>(-JD{{ number_format($item->discount_value, 2) }})</small>
                            @else
                                -
                            @endif
                        </td>
                        <td><strong>JD{{ number_format($item->total_price, 2) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="invoice-summary">
            <div class="row">
                <div class="col-6">
                    <div class="payment-info">
                        <h5>{{ __('messages.payment_information') }}</h5>
                        <p>{{ __('messages.payment_method') }}: {{ ucfirst($order->payment_type) }}</p>
                        <p>{{ __('messages.order_date') }}: {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="summary-table">
                        <table class="total-table">
                            <tr>
                                <td>{{ __('messages.subtotal') }}:</td>
                                <td>JD{{ number_format($order->total_prices + $order->total_discounts, 2) }}</td>
                            </tr>
                            @if($order->total_discounts > 0)
                            <tr>
                                <td>{{ __('messages.discount') }}:</td>
                                <td class="discount">-JD{{ number_format($order->total_discounts, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>{{ __('messages.delivery_fee') }}:</td>
                                <td>JD{{ number_format($order->delivery_fee, 2) }}</td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>{{ __('messages.total_amount') }}:</strong></td>
                                <td><strong>JD{{ number_format($order->total_prices + $order->delivery_fee, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <div class="row">
                <div class="col-12">
                    
                    <div class="thank-you">
                        <p><em>{{ __('messages.thank_you_for_business') }}</em></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')
<script>
    // Auto-focus print dialog when page loads (optional)
    // window.onload = function() {
    //     window.print();
    // }
</script>