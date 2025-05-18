@extends('layouts.user')

@section('content')
<section class="menu-section">
  <h2 class="menu-title">{{ __('messages.Menu') }}</h2>
  
  <!-- Categories Tabs -->
  <ul class="menu-tabs">
    @foreach($categories as $category)
      <li class="menu-tab {{ $category->id == $firstCategoryId ? 'active' : '' }}" data-category="{{ $category->id }}">
        {{ $locale == 'en' ? $category->name_en : $category->name_ar }}
      </li>
    @endforeach
  </ul>
  
  <!-- Products -->
  <div class="menu-products">
    @foreach($categories as $category)
      <div class="menu-category" id="category-{{ $category->id }}" style="{{ $category->id == $firstCategoryId ? '' : 'display: none;' }}">
        <h3 class="category-title">{{ $locale == 'en' ? $category->name_en : $category->name_ar }}</h3>
        <div class="menu-grid">
          @forelse($category->products->where('status', 1) as $product)
          <a href="{{ route('product.details',$product->id) }}">
            <div class="menu-card">
              <img src="{{ asset('assets/admin/uploads/' . $product->productImages->first()->photo) }}" alt="{{ $locale == 'en' ? $product->name_en : $product->name_ar }}" />
              <p>{{ $locale == 'en' ? $product->name_en : $product->name_ar }}</p>
              @if($product->offers->count() > 0)
                <span class="product-offer">{{ __('messages.On Sale') }}</span>
              @endif
              <p class="product-price">{{ $product->selling_price }} JD</p>
            </div>
          </a>
          @empty
            <div class="no-products">
              <p>{{ __('messages.No products available') }}</p>
            </div>
          @endforelse
        </div>
      </div>
    @endforeach
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.menu-tab');
    
    tabs.forEach(tab => {
      tab.addEventListener('click', function() {
        // Remove active class from all tabs
        tabs.forEach(t => t.classList.remove('active'));
        
        // Add active class to current tab
        this.classList.add('active');
        
        // Hide all categories
        document.querySelectorAll('.menu-category').forEach(category => {
          category.style.display = 'none';
        });
        
        // Show the selected category
        const categoryId = this.getAttribute('data-category');
        document.getElementById('category-' + categoryId).style.display = 'block';
      });
    });
  });
</script>
@endpush
@endsection