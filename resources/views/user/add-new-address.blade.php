@extends('layouts.user')
@section('css')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
.map-container {
  position: relative;
  width: 100%;
  height: 400px;
  margin-bottom: 20px;
  border-radius: 10px;
  overflow: hidden;
}

#map {
  width: 100%;
  height: 100%;
  z-index: 1;
}

.map-controls {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 10;
}

.current-location-btn {
  background-color: white;
  border: none;
  border-radius: 4px;
  padding: 8px 12px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
  font-size: 14px;
  display: flex;
  align-items: center;
  cursor: pointer;
}

.current-location-btn i {
  margin-right: 6px;
}

.delivery-select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 16px;
  margin-bottom: 10px;
}

.delivery-info {
  background-color: #f8f9fa;
  border-radius: 8px;
  padding: 15px;
  margin: 15px 0;
}

.delivery-fee {
  display: flex;
  justify-content: space-between;
  font-size: 16px;
}

.loading-location {
  padding: 5px 10px;
  font-style: italic;
}

.leaflet-popup-content {
  margin: 10px;
  font-size: 14px;
}
</style>
@endsection

@section('content')
<section class="location-section">
    <div class="map-container">
      <div class="map-controls">
        <button type="button" id="currentLocationBtn" class="current-location-btn">
          <i class="fa fa-crosshairs"></i> {{ __('messages.Use My Current Location') }}
        </button>
      </div>
      <div id="map"></div>
    </div>
    
    <form action="{{ route('address.store') }}" method="POST" class="address-form">
      @csrf
      
      @if(isset($fromCheckout) && $fromCheckout)
          <input type="hidden" name="from_checkout" value="1">
      @endif
      
      <div class="address-inputs">
        <div class="address-field">
          <label>{{ __('messages.Delivery Area') }}</label>
          <select name="delivery_id" id="deliveryArea" class="delivery-select" required>
            <option value="">{{ __('messages.Select delivery area') }}</option>
            @foreach ($deliveries as $delivery)
              <option value="{{ $delivery->id }}" data-price="{{ $delivery->price }}">
                {{ $delivery->place }} - {{ $delivery->price }} JD
              </option>
            @endforeach
          </select>
          @error('delivery_id')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <div class="address-field">
          <label>{{ __('messages.Address') }}</label>
          <input type="text" name="address" id="addressInput" placeholder="{{ __('messages.E.g., Oak Street') }}" value="{{ old('address') }}" required />
          @error('address')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="address-field">
          <label>{{ __('messages.Building Number') }}</label>
          <input type="text" name="building_number" placeholder="{{ __('messages.E.g., 45') }}" value="{{ old('building_number') }}" required />
          @error('building_number')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="address-field">
          <label>{{ __('messages.Floor') }}</label>
          <input type="text" name="floor" placeholder="{{ __('messages.E.g., 3rd') }}" value="{{ old('floor') }}" required />
          @error('floor')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="address-field">
          <label>{{ __('messages.Apartment Number') }}</label>
          <input type="text" name="apartment_number" placeholder="{{ __('messages.E.g., Apt 5B') }}" value="{{ old('apartment_number') }}" required />
          @error('apartment_number')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <!-- Hidden fields for coordinates -->
        <input type="hidden" name="lat" id="lat" value="{{ old('lat', '31.9539') }}" required>
        <input type="hidden" name="lng" id="lng" value="{{ old('lng', '35.9106') }}" required>
        
      </div>
      

      
      <div class="confirm-button-container">
        <button type="submit" class="confirm-location-btn">{{ __('messages.Confirm Your Location') }}</button>
      </div>
    </form>
</section>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize map variables
    let map, marker, popup;
    let defaultLat = parseFloat(document.getElementById('lat').value) || 31.9539; // Amman default
    let defaultLng = parseFloat(document.getElementById('lng').value) || 35.9106; // Amman default
    
    // Initialize the map
    map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Create a custom icon for the marker
    const customIcon = L.icon({
      iconUrl: '{{ asset("assets_front/assets/images/pin.png") }}',
      iconSize: [40, 40],
      iconAnchor: [20, 40],
      popupAnchor: [0, -40]
    });
    
    // Add a draggable marker
    marker = L.marker([defaultLat, defaultLng], {
      draggable: true,
      icon: customIcon || L.Icon.Default()
    }).addTo(map);
    
    // Create a popup for the marker
    popup = L.popup().setContent('{{ __("messages.Drag me to your location") }}');
    marker.bindPopup(popup).openPopup();
    
    // Update coordinates when marker is dragged
    marker.on('dragend', function(event) {
      const latlng = marker.getLatLng();
      updateLocation(latlng);
    });
    
    // Allow clicking on map to move marker
    map.on('click', function(event) {
      marker.setLatLng(event.latlng);
      updateLocation(event.latlng);
    });
    
    // Set up geocoder
    const geocoder = L.Control.geocoder({
      defaultMarkGeocode: false
    }).on('markgeocode', function(e) {
      const latlng = e.geocode.center;
      marker.setLatLng(latlng);
      map.setView(latlng, 15);
      updateLocation(latlng);
    }).addTo(map);
    
    // Set up current location button
    document.getElementById('currentLocationBtn').addEventListener('click', function() {
      popup.setContent('<div class="loading-location">{{ __("messages.Getting your location...") }}</div>');
      marker.openPopup();
      
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            const latlng = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            
            marker.setLatLng(latlng);
            map.setView(latlng, 15);
            updateLocation(latlng);
            
            popup.setContent('{{ __("messages.Your current location") }}');
          },
          function(error) {
            popup.setContent('{{ __("messages.Error getting location") }}: ' + error.message);
          },
          {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
          }
        );
      } else {
        alert('{{ __("messages.Geolocation is not supported by your browser") }}');
      }
    });
    
    // Function to update location and form inputs
    function updateLocation(latlng) {
      // Update hidden inputs with coordinates
      document.getElementById('lat').value = latlng.lat;
      document.getElementById('lng').value = latlng.lng;
      
      // Get address from coordinates using reverse geocoding
      fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
          if (data && data.display_name) {
            document.getElementById('addressInput').value = data.display_name;
            popup.setContent(data.display_name);
            marker.openPopup();
          }
        })
        .catch(error => {
          console.error('Error fetching address:', error);
        });
    }
    
    // Update delivery fee when area is selected
    document.getElementById('deliveryArea').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const deliveryFee = selectedOption.getAttribute('data-price') || 0;
      document.getElementById('deliveryFee').textContent = parseFloat(deliveryFee).toFixed(2) + ' JD';
    });
  });
</script>
@endpush