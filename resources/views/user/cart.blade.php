@extends('layouts.user')

@section('content')
<section class="cart-section">
    <div class="cart-container">
      <h2 class="cart-title">{{ __('messages.Your Cart') }}</h2>
      
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      
      @if($cartItems->count() > 0)
        <form action="{{ route('cart.update') }}" method="POST">
          @csrf
          <table class="cart-table">
            <thead>
              <tr>
                <th></th>
                <th>{{ __('messages.Item') }}</th>
                <th>{{ __('messages.Price') }}</th>
                <th>{{ __('messages.Quantity') }}</th>
                <th>{{ __('messages.Subtotal') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cartItems as $item)
                <tr>
                  <td class="cart-remove">
                    <a href="{{ route('cart.remove', $item->id) }}" onclick="return confirm('{{ __('messages.Are you sure you want to remove this item?') }}')">x</a>
                  </td>
                  <td class="cart-item">
                    @if($item->product->productImages->count() > 0)
                      <img src="{{ asset('assets/admin/uploads/' . $item->product->productImages->first()->photo) }}" alt="{{ $item->product->name_en }}">
                    @else
                      <img src="{{ asset('assets/images/placeholder.png') }}" alt="{{ $item->product->name_en }}">
                    @endif
                    <div>
                      {{ app()->getLocale() == 'en' ? $item->product->name_en : $item->product->name_ar }}
                      @if($item->offer)
                        <span class="offer-badge">{{ __('messages.On Sale') }}</span>
                      @endif
                    </div>
                  </td>
                  <td class="cart-price"><strong>{{ $item->price }} JD</strong></td>
                  <td class="cart-quantity">
                    <input type="number" name="quantity[{{ $item->id }}]" min="1" value="{{ $item->quantity }}">
                  </td>
                  <td class="cart-subtotal"><strong>{{ $item->total_price_product }} JD</strong></td>
                </tr>
              @endforeach
            </tbody>
          </table>
          
          <div class="cart-actions">
            <div class="coupon-box">
              <input type="text" name="code" placeholder="{{ __('messages.Coupon Code') }}">
              <button type="submit" formaction="{{ route('cart.apply-coupon') }}" class="apply-coupon-btn">{{ __('messages.Apply Coupon') }}</button>
            </div>
            <button type="submit" class="update-cart-btn"><i class="fa fa-rotate-right"></i> {{ __('messages.Update Cart') }}</button>
          </div>
        </form>
        
        <div class="cart-totals">
          <h3>{{ __('messages.Cart Totals') }}</h3>
          <table>
            <tr>
              <td>{{ __('messages.Subtotal') }}</td>
              <td>{{ $subtotal }} JD</td>
            </tr>
            @if($totalDiscount > 0)
              <tr>
                <td>{{ __('messages.Discount') }}</td>
                <td>{{ $totalDiscount }} JD</td>
              </tr>
            @endif
         
            <tr>
              <td>{{ __('messages.Total') }}</td>
              <td><strong>{{ $total }} JD</strong></td>
            </tr>
          </table>
          <form action="{{ route('checkout') }}" method="GET">
            <button type="submit" class="checkout-btn">{{ __('messages.Checkout') }}</button>
          </form>
        </div>
      @else
        <div class="empty-cart">
          <p>{{ __('messages.Your cart is empty') }}</p>
          <a href="{{ route('menu') }}" class="continue-shopping">{{ __('messages.Continue Shopping') }}</a>
        </div>
      @endif
    </div>
</section>
@endsection