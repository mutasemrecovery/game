@extends('layouts.user')

@section('content')
<section class="orders-section">
    <div class="orders-header">
      <h2 class="orders-title">{{ __('messages.My Orders') }} ({{ $orders->count() }})</h2>
      <div class="orders-status-legend">
        <span class="legend-item pending">● {{ __('messages.Pending') }}</span>
        <span class="legend-item confirmed">● {{ __('messages.Confirmed') }}</span>
        <span class="legend-item on-way">● {{ __('messages.On the way') }}</span>
        <span class="legend-item delivered">● {{ __('messages.Delivered') }}</span>
        <span class="orders-total">{{ __('messages.Total') }} {{ $totalAmount }} JD</span>
      </div>
    </div>
  
    <div class="orders-list">
      @forelse($orders as $order)
        <div class="order-card">
          <div class="order-col status-col">
            @php
              $statusClass = '';
              $statusInitial = '';
              
              switch($order->order_status) {
                case 1:
                  $statusClass = 'pending';
                  $statusInitial = 'Pe';
                  break;
                case 2:
                  $statusClass = 'on-way';
                  $statusInitial = 'Ow';
                  break;
                case 3:
                  $statusClass = 'cancelled';
                  $statusInitial = 'Ca';
                  break;
                case 4:
                  $statusClass = 'failed';
                  $statusInitial = 'Fa';
                  break;
                case 5:
                  $statusClass = 'refund';
                  $statusInitial = 'Re';
                  break;
                case 6:
                  $statusClass = 'delivered';
                  $statusInitial = 'De';
                  break;
                default:
                  $statusClass = 'pending';
                  $statusInitial = 'Pe';
              }
            @endphp
            <div class="status-circle {{ $statusClass }}">{{ $statusInitial }}</div>
          </div>
          
          <div class="order-col id-col">
            #{{ $order->id }}
          </div>
          
          <div class="order-col time-col">
            <div>{{ $order->date->format('H:i') }}</div>
            <div>{{ $order->date->format('d/m/y') }}</div>
          </div>
          
          <div class="order-col time-col">
            {{ $order->branch->name }}
          </div>
          <div class="order-col details-col">
            @php $products = $order->products()->with('product')->take(3)->get(); @endphp
            @foreach($products as $item)
              {{ $item->product->name_en }} x {{ $item->quantity }}<br>
            @endforeach
            @if($order->products->count() > 3)
              <span class="more-items">+ {{ $order->products->count() - 3 }} {{ __('messages.more items') }}</span>
            @endif
          </div>
          
          <div class="order-col price-col">
            {{ $order->total_prices }} JD
          </div>
          
          <div class="order-col state-col {{ $statusClass }}-text">
            {{ $order->getStatusText() }}
          </div>
          
          <div class="order-col arrow-col">
            <i class="fa fa-chevron-right"></i>
          </div>
        </div>
      @empty
        <div class="no-orders">
          <i class="fa fa-shopping-bag fa-4x"></i>
          <h3>{{ __('messages.No orders yet') }}</h3>
          <p>{{ __('messages.You haven\'t placed any orders yet') }}</p>
          <a href="{{ route('menu') }}" class="browse-menu-btn">{{ __('messages.Browse Menu') }}</a>
        </div>
      @endforelse
    </div>
    
    @if($orders->count() > 0 && method_exists($orders, 'links'))
    <div class="orders-pagination">
      {{ $orders->links() }}
    </div>
    @endif
  </section>
@endsection