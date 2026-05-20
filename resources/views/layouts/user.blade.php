<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>@yield('title')</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="{{ asset('assets/admin/fonts/SansPro/SansPro.min.css') }}">
    @if (App::getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets_front/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <style>
        .booked-product {
            pointer-events: none;
            opacity: 0.6;
            position: relative;
        }
        .booked-product .product-image-container button {
            pointer-events: auto;
        }
        .booked-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            border-radius: inherit;
        }
        .booked-overlay span {
            background: #dc3545;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 6px 22px;
            border-radius: 4px;
            letter-spacing: 1px;
        }
        .product-card {
            position: relative;
        }
        .step1-fixed-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid #dee2e6;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.08);
        }
        .products-section-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: #333;
        }
        #modalMainImg {
            max-height: 75vh;
            object-fit: contain;
        }
        .img-hint {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.45);
            color: #fff;
            font-size: 0.72rem;
            text-align: center;
            padding: 4px 6px;
            pointer-events: none;
        }
        @media (max-width: 576px) {
            .step1-fixed-nav {
                padding: 6px 20px;
            }
            .step1-fixed-nav .btn-lg {
                padding: 6px 14px;
                font-size: 0.9rem;
            }
        }

        /* ── Gallery thumbnail strip ── */
        #gallery-section {
            display: none;
            width: 100%;
            background: #111;
            padding: 6px;
            cursor: pointer;
        }
        .gallery-thumb-strip {
            display: flex;
            gap: 4px;
            overflow: hidden;
        }
        .gallery-thumb {
            flex: 1 1 0;
            aspect-ratio: 1;
            max-width: 33.33%;
            position: relative;
            overflow: hidden;
            border-radius: 3px;
        }
        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .gallery-thumb-more {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.55);
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .gallery-thumb-hint {
            text-align: center;
            color: rgba(255,255,255,0.6);
            font-size: 0.72rem;
            padding: 4px 0 2px;
            letter-spacing: .5px;
        }

        /* ── Full-screen viewer ── */
        #fs-gallery {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #000;
            display: none;
            flex-direction: column;
        }
        #fs-gallery .fs-top-bar {
            position: absolute;
            top: 0; left: 0; right: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.65) 0%, transparent 100%);
        }
        #fs-gallery .fs-counter {
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: .5px;
        }
        #fs-gallery .fs-close {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.6rem;
            line-height: 1;
            cursor: pointer;
            padding: 0 4px;
        }
        #fs-gallery .swiper {
            width: 100%;
            height: 100%;
        }
        #fs-gallery .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }
        #fs-gallery .swiper-slide img {
            max-width: 100%;
            max-height: 100vh;
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            user-select: none;
            -webkit-user-drag: none;
        }
        #fs-gallery .swiper-button-next,
        #fs-gallery .swiper-button-prev {
            color: rgba(255,255,255,0.75) !important;
        }
        #fs-gallery .swiper-button-next::after,
        #fs-gallery .swiper-button-prev::after {
            font-size: 1.1rem !important;
        }

        /* ── Floating WhatsApp ── */
        #float-whatsapp {
            position: fixed;
            bottom: 80px;
            left: 18px;
            z-index: 999;
            background: #25d366;
            color: #fff;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            box-shadow: 0 4px 14px rgba(37,211,102,0.5);
            text-decoration: none;
            transition: transform 0.2s;
        }
        #float-whatsapp:hover { transform: scale(1.12); }

        /* ── Pledge checkbox ── */
        .pledge-wrapper {
            background: #fff8e1;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
        }
        .pledge-wrapper label { cursor: pointer; font-weight: 500; }
    </style>

    @yield('css')
</head>

<body>

    <div class="steps-header">
        <h3 class="text-center mb-1" id="header-step1">{{ __('messages.Character Photos') }}</h3>
        <h3 class="text-center" id="header-step3" style="display:none;">{{ __('messages.Initial Booking') }}</h3>
    </div>

    <!-- ── Gallery thumbnail strip (step 1 preview) ── -->
    <div id="gallery-section" onclick="openFsGallery(0)">
        <div class="gallery-thumb-strip" id="gallery-thumbs"></div>
        <p class="gallery-thumb-hint">{{ __('messages.Tap to view all photos') }}</p>
    </div>

    <!-- ── Full-screen photo viewer (like phone gallery) ── -->
    <div id="fs-gallery">
        <div class="fs-top-bar">
            <span class="fs-counter" id="fs-counter">1 / 1</span>
            <button class="fs-close" onclick="closeFsGallery()">&#x2715;</button>
        </div>
        <div class="swiper" id="fsSwiper">
            <div class="swiper-wrapper" id="fs-slides"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <!-- ── Floating WhatsApp (step 3 only) ── -->
    <a id="float-whatsapp" href="https://wa.me/{{ env('WHATSAPP_NUMBER', '962776648373') }}" target="_blank" rel="noopener" style="display:none;">
        <i class="fab fa-whatsapp"></i>
    </a>

    <div class="container" id="main-container">

        <form action="{{ route('userOrders.store') }}" method="post" enctype='multipart/form-data'>
            @csrf

            <!-- Step 1: Date & Products -->
            <div class="step-content active" id="step1">
                
                <div class="custom-card card">
                 

                    <div class="card-body">
                        <!-- Date picker -->
                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-3">
                                   <div class="card-header bg-transparent border-0 pt-4">
                      
                        <p class="text-center text-muted mb-0">{!! __('messages.Select Order Date') !!}</p>
                    </div>
                                <input type="text" id="order_date" name="date"
                                    class="form-control form-control-lg" required placeholder="{{ __('messages.Select Date') }}">

                                @error('date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Products section -->
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                              
                                <div style="max-width: 320px; width: 100%;">
                                    <input type="text" id="product-search" class="form-control"
                                        placeholder="{{ __('messages.Search By Name') }}">
                                </div>
                            </div>

                            <div id="products-loading" class="text-center py-5">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">{{ __('messages.Loading products...') }}</span>
                                </div>
                                <p class="mt-2 text-muted">{{ __('messages.Loading available products...') }}</p>
                            </div>

                            <div id="products-container" class="product-grid" style="display: none;">
                                <!-- Products loaded here -->
                            </div>

                            <!-- Inline notice shown when user tries to select without a date -->
                            <div id="no-date-notice" style="display:none;"
                                 class="alert alert-warning mt-3 text-center">
                                <i class="fas fa-calendar-alt me-2"></i>
                                {{ __('messages.Please select a date first to check availability') }}
                                <button type="button" class="btn btn-sm btn-primary ms-3" onclick="openDatePicker()">
                                    {{ __('messages.Select Date') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fixed bottom nav for step 1 -->
                <div class="step1-fixed-nav" id="step1-nav" style="display:none;">
                    <button type="button" class="btn btn-secondary btn-lg" onclick="history.back()">
                        <i class="fas fa-arrow-right me-2"></i> {{ __('messages.Back') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" onclick="goToCart()" disabled
                        id="review-cart-btn">
                        {{ __('messages.Review Cart') }} <i class="fas fa-arrow-left ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Review Cart -->
            <div class="step-content" id="step2">
                <div class="custom-card card">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h3 class="text-center">{{ __('messages.Review Your Cart') }}</h3>
                    </div>
                    <div class="card-body">
                        <div id="cart-items">
                            <!-- Cart items displayed here -->
                        </div>

                    </div>
                </div>

                <!-- Fixed bottom nav for step 2 -->
                <div class="step1-fixed-nav" id="step2-nav" style="display:none;">
                    <button type="button" class="btn btn-secondary btn-lg" onclick="previousStep(2)">
                        <i class="fas fa-arrow-right me-2"></i> {{ __('messages.Back') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(2)">
                        {{ __('messages.Proceed to Checkout') }} <i class="fas fa-arrow-left ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Checkout -->
            <div class="step-content" id="step3">
                <div class="row">
                    <div class="col-md-7">
                        <div class="custom-card card">
                            {{-- <div class="card-header bg-transparent border-0 pt-4">
                               
                            </div> --}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">{{ __('messages.Customer Name') }}</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">{{ __('messages.Customer Phone') }}</label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                            value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">{{ __('messages.Customer Address') }}</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            value="{{ old('address') }}" required>
                                        @error('address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <select class="form-select select2" id="delivery_id" name="delivery_id">
                                            <option value="" data-price="0" selected>
                                                {{ __('messages.Select Delivery') }}</option>

                                            @foreach ($deliveries as $delivery)
                                                <option value="{{ $delivery->id }}"
                                                    data-price="{{ $delivery->price }}"
                                                    {{ old('delivery_id') == $delivery->id ? 'selected' : '' }}>
                                                    {{ $delivery->place }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('delivery_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Time picker -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.Order Time') }}</label>
                                        <div class="d-flex gap-2">
                                            <select id="time_hour" class="form-control" >
                                                @for($h = 1; $h <= 12; $h++)
                                                    <option value="{{ $h }}">{{ $h }}</option>
                                                @endfor
                                            </select>
                                            <span class="form-control d-flex align-items-center justify-content-center"
                                                  style="width:auto; min-width:48px; font-weight:600; background:#f8f9fa; cursor:default;">
                                                م
                                            </span>
                                            <input type="hidden" id="time_period" value="PM">
                                        </div>
                                        <small style="color: red">{{ __('messages.Note for Time') }}</small>
                                        <input type="hidden" name="order_time" id="order_time">
                                    </div>

                                    <!-- Note field -->
                                    <div class="col-md-12 mb-3">
                                        <textarea class="form-control" id="note" name="note" rows="3"
                                            placeholder="{{ __('messages.Optional note') }}">{{ old('note') }}</textarea>
                                        @error('note')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <!-- Pledge checkbox -->
                                <div class="pledge-wrapper mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="pledge_checkbox">
                                        <label class="form-check-label" for="pledge_checkbox">
                                            {{ __('messages.I pledge to return the character on the agreed day') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-secondary btn-lg me-3"
                                        onclick="previousStep(3)">
                                        <i class="fas fa-arrow-left me-2"></i> {{ __('messages.Back') }}
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg"
                                        id="place-order-btn" onclick="return placeOrder()">
                                        <i class="fas fa-check me-2"></i> {{ __('messages.Place Order') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="checkout-summary">
                            <h4 class="mb-3">{{ __('messages.Order Summary') }}</h4>
                            <div id="checkout-summary-content">
                                <!-- Summary populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden fields -->
            <input type="hidden" name="total_prices" id="hidden_total_prices">
            <input type="hidden" name="total_discounts" id="hidden_total_discounts">
            <input type="hidden" name="products_data" id="hidden_products_data">
            <input type="hidden" name="products[]" id="hidden_products">
        </form>
    </div>

    <!-- Photo Gallery Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="padding:10px 15px;">
                    <h5 class="modal-title" id="imageModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="background:#111; position:relative; min-height:200px; display:flex; align-items:center; justify-content:center;">
                    <img id="modalMainImg" src="" alt=""
                         style="display:block; max-height:75vh; max-width:100%; width:100%; object-fit:contain;">
                    <!-- prev -->
                    <button id="modalPrevBtn" type="button" onclick="changeModalPhoto(-1)"
                            style="display:none; position:absolute; left:0; top:0; bottom:0; background:rgba(0,0,0,0.35); border:none; color:#fff; padding:0 14px; font-size:1.4rem; cursor:pointer;">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <!-- next -->
                    <button id="modalNextBtn" type="button" onclick="changeModalPhoto(1)"
                            style="display:none; position:absolute; right:0; top:0; bottom:0; background:rgba(0,0,0,0.35); border:none; color:#fff; padding:0 14px; font-size:1.4rem; cursor:pointer;">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <!-- counter -->
                    <span id="photoCounter"
                          style="display:none; position:absolute; bottom:10px; left:50%; transform:translateX(-50%); background:rgba(0,0,0,0.55); color:#fff; padding:2px 10px; border-radius:12px; font-size:0.85rem;"></span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        flatpickr("#order_date", {
            enableTime: false,
            dateFormat: "Y-m-d",
            disableMobile: true,
            locale: {
                weekdays: {
                    shorthand: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                    longhand: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                },
                months: {
                    shorthand: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
                    longhand: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"]
                },
            }
        });
    </script>

    <script>
        let selectedProducts = {};
        let currentStep = 1;
        let productsData = [];

        $(document).ready(function () {
            $('.select2').select2({ theme: 'bootstrap-5' });

            // Load all products on page start so user can browse
            fetchAllProducts();

            // When date changes, reload products with availability info
            $('#order_date').on('change', function () {
                const date = $(this).val();
                $('#no-date-notice').hide();
                if (date) {
                    fetchAvailableProducts(date);
                } else {
                    fetchAllProducts();
                }
                updateNavVisibility();
            });

            $('#delivery_id').on('change', function () {
                updateCheckoutSummary();
            });
        });

        // ── Navigation ──────────────────────────────────────────────

        function goToCart() {
            if (!$('#order_date').val()) {
                $('#order_date').addClass('is-invalid');
                return;
            }
            $('#order_date').removeClass('is-invalid');

            if (Object.keys(selectedProducts).length === 0) {
                alert('{{ __('messages.Please select at least one product') }}');
                return;
            }
            updateCartDisplay();
            currentStep = 2;
            showStep(2);
        }

        function nextStep(step) {
            if (step === 2) {
                updateCheckoutSummary();
                currentStep = 3;
                showStep(3);
            }
        }

        function previousStep(step) {
            currentStep = step - 1;
            showStep(currentStep);
        }

        function showStep(step) {
            $('.step-content').removeClass('active');
            $('#step' + step).addClass('active');

            $('#header-step1').toggle(step === 1);
            $('#header-step3').toggle(step === 3);
            $('#float-whatsapp').toggle(step === 3);
            if (step !== 1) {
                $('#gallery-section').hide();
                closeFsGallery();
            } else if (_allPhotos.length > 0) {
                $('#gallery-section').show();
            }

            if (step === 1) {
                $('#step2-nav').hide();
                updateNavVisibility();
            } else if (step === 2) {
                $('#step1-nav').hide();
                $('#step2-nav').show();
                $('#main-container').css('padding-bottom', '90px');
            } else {
                $('#step1-nav').hide();
                $('#step2-nav').hide();
                $('#main-container').css('padding-bottom', '20px');
            }

            window.scrollTo(0, 0);
        }

        // ── Product loading ──────────────────────────────────────────

        function fetchAllProducts() {
            showProductsLoading();
            $.ajax({
                url: '{{ route('user.orders.all-products') }}',
                method: 'GET',
                success: function (response) {
                    productsData = response.products;
                    displayProducts(response.products);
                    buildGalleryFromProducts(response.products);
                },
                error: function () {
                    hideProductsLoading();
                    alert('{{ __('messages.Error loading products. Please try again') }}');
                }
            });
        }

        function fetchAvailableProducts(date) {
            showProductsLoading();
            $.ajax({
                url: '{{ route('user.orders.available-products') }}',
                method: 'GET',
                data: { date: date },
                success: function (response) {
                    productsData = response.products;
                    displayProducts(response.products);
                    buildGalleryFromProducts(response.products);
                },
                error: function () {
                    hideProductsLoading();
                    alert('{{ __('messages.Error loading products. Please try again') }}');
                }
            });
        }

        function showProductsLoading() {
            $('#products-loading').show();
            $('#products-container').hide();
        }

        function hideProductsLoading() {
            $('#products-loading').hide();
            $('#products-container').show();
        }

        // ── Search ───────────────────────────────────────────────────

        $(document).on('input', '#product-search', function () {
            const term = $(this).val().toLowerCase().trim();
            $('.product-card').each(function () {
                const pid = $(this).data('product-id');
                const p = productsData.find(x => x.id === pid);
                if (!p) return;
                const match = !term
                    || (p.name_ar || '').toLowerCase().includes(term)
                    || (p.name_en || '').toLowerCase().includes(term)
                    || (p.description_ar || p.description_en || '').toLowerCase().includes(term);
                $(this).toggle(match);
            });
        });

        // ── Display products ─────────────────────────────────────────

        function displayProducts(products) {
            // Booked products appear last
            products = [...products].sort((a, b) => (a.booked ? 1 : 0) - (b.booked ? 1 : 0));

            $('#product-search').val('');
            const container = $('#products-container');
            container.empty();

            if (products.length === 0) {
                container.html(
                    '<div class="empty-state"><i class="fas fa-box-open fa-3x mb-3"></i><p>{{ __('messages.No products available for the selected date') }}</p></div>'
                );
                hideProductsLoading();
                return;
            }

            products.forEach(function (product) {
                const discount = product.offer_price
                    ? ((product.selling_price - product.offer_price) / product.selling_price * 100).toFixed(0)
                    : 0;
                const isBooked = product.booked === true;
                const name = product.name_en || product.name_ar;

                const card = `
                    <div class="product-card ${isBooked ? 'booked-product' : ''}"
                         data-product-id="${product.id}"
                         ${!isBooked ? `onclick="toggleProduct(${product.id})"` : ''}>

                        ${isBooked ? `<div class="booked-overlay"><span>{{ __('messages.Booked') }}</span></div>` : ''}

                        <div class="product-image-container">
                            <button type="button"
                                    onclick="viewProductPhotos(${product.id}, event)">
                                <img src="${product.image}" alt="${name}" class="product-image">
                                ${discount > 0 ? `<span class="discount-badge">-${discount}%</span>` : ''}
                                <i class="fas fa-search-plus"></i>
                                <span class="img-hint">{{ __('messages.Click to view full size') }}</span>
                            </button>
                        </div>

                        <div class="product-info">
                            <div class="info-row">
                                <div>
                                    <h5 class="product-name">${name}</h5>
                                    <div class="product-prices">
                                        <span style="color:#1a2ebd; font-size:0.8rem; font-weight:600;">{{ __('messages.Rental Price') }}</span>
                                        ${product.offer_price
                                            ? `<span class="price-original">JD ${product.selling_price}</span>
                                               <span class="price-offer">JD ${product.offer_price}</span>`
                                            : `<span class="price-current">JD ${product.selling_price}</span>`
                                        }
                                    </div>
                                </div>
                                ${!isBooked ? `<button type="button" class="select-button">{{ __('messages.Select') }}</button>` : ''}
                            </div>
                        </div>

                        ${!isBooked ? `<div class="selected-overlay"><i class="fas fa-check-circle"></i></div>` : ''}
                    </div>
                `;
                container.append(card);
            });

            // Restore previously selected state
            Object.keys(selectedProducts).forEach(id => {
                $(`.product-card[data-product-id="${id}"]`).addClass('selected');
            });

            hideProductsLoading();
        }

        // ── Toggle selection ─────────────────────────────────────────

        function openDatePicker() {
            const fp = document.getElementById('order_date')._flatpickr;
            document.getElementById('order_date').scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (fp) fp.open();
        }

        function toggleProduct(productId) {
            if (!$('#order_date').val()) {
                // Show inline notice and highlight the date field
                $('#no-date-notice').show();
                $('#order_date').addClass('is-invalid');
                document.getElementById('order_date').scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => $('#order_date').removeClass('is-invalid'), 2500);
                return;
            }
            $('#no-date-notice').hide();

            const card = $(`.product-card[data-product-id="${productId}"]`);
            if (selectedProducts[productId]) {
                delete selectedProducts[productId];
                card.removeClass('selected');
            } else {
                const product = productsData.find(p => p.id === productId);
                if (!product || product.booked) return;
                selectedProducts[productId] = { ...product, quantity: 1 };
                card.addClass('selected');
            }
            updateReviewCartButton();
            updateNavVisibility();
            updateHiddenFields();
        }

        function updateNavVisibility() {
            const hasDate = !!$('#order_date').val();
            const hasProducts = Object.keys(selectedProducts).length > 0;
            if (hasDate && hasProducts) {
                $('#step1-nav').show();
                $('#main-container').css('padding-bottom', '90px');
            } else {
                $('#step1-nav').hide();
                $('#main-container').css('padding-bottom', '20px');
            }
        }

        function updateReviewCartButton() {
            const btn = $('#review-cart-btn');
            const count = Object.keys(selectedProducts).length;
            if (count > 0) {
                btn.prop('disabled', false);
                btn.html(`{{ __('messages.Review Cart') }} (${count}) <i class="fas fa-arrow-left ms-2"></i>`);
            } else {
                btn.prop('disabled', true);
                btn.html('{{ __('messages.Review Cart') }} <i class="fas fa-arrow-left ms-2"></i>');
            }
        }

        // ── Cart display ─────────────────────────────────────────────

        function updateCartDisplay() {
            const container = $('#cart-items');
            container.empty();

            if (Object.keys(selectedProducts).length === 0) {
                container.html(
                    '<div class="empty-state"><i class="fas fa-shopping-cart fa-3x mb-3"></i><p>{{ __('messages.Your cart is empty') }}</p></div>'
                );
                return;
            }

            Object.values(selectedProducts).forEach(product => {
                const item = `
                    <div class="cart-item">
                        <img src="${product.image}" alt="${product.name_en || product.name_ar}" class="cart-item-image">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${product.name_en || product.name_ar}</h6>
                            <p class="mb-0 text-muted">
                                ${product.offer_price
                                    ? `<span class="text-decoration-line-through me-2">JD ${product.selling_price}</span>
                                       <span class="text-danger fw-bold">JD ${product.offer_price}</span>`
                                    : `<span>JD ${product.selling_price}</span>`
                                }
                            </p>
                        </div>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn" onclick="updateQuantity(${product.id}, -1)">-</button>
                            <input type="number" class="quantity-input" value="${product.quantity}" min="1"
                                onchange="updateQuantity(${product.id}, 0, this.value)">
                            <button type="button" class="quantity-btn" onclick="updateQuantity(${product.id}, 1)">+</button>
                        </div>
                        <div class="ms-3">
                            <strong>JD ${((product.offer_price || product.selling_price) * product.quantity).toFixed(2)}</strong>
                        </div>
                    </div>
                `;
                container.append(item);
            });
        }

        function updateQuantity(productId, change, newValue = null) {
            if (newValue !== null) {
                selectedProducts[productId].quantity = Math.max(1, parseInt(newValue) || 1);
            } else {
                selectedProducts[productId].quantity = Math.max(1, selectedProducts[productId].quantity + change);
            }
            updateCartDisplay();
            updateCheckoutSummary();
            updateHiddenFields();
        }

        // ── Checkout summary ─────────────────────────────────────────

        function updateCheckoutSummary() {
            let subtotal = 0;
            let totalDiscount = 0;

            Object.values(selectedProducts).forEach(product => {
                const price = product.selling_price;
                const offerPrice = product.offer_price || price;
                subtotal += offerPrice * product.quantity;
                totalDiscount += (price - offerPrice) * product.quantity;
            });

            const deliveryFee = parseFloat($('#delivery_id').find('option:selected').data('price')) || 0;
            const total = subtotal + deliveryFee;

            $('#checkout-summary-content').html(`
                <div class="summary-item">
                    <span>{{ __('messages.Subtotal') }}:</span>
                    <span>JD ${subtotal.toFixed(2)}</span>
                </div>
                <div class="summary-item">
                    <span>{{ __('messages.Delivery Fee') }}:</span>
                    <span>JD ${deliveryFee.toFixed(2)}</span>
                </div>
                <div class="summary-item total">
                    <span>{{ __('messages.Total') }}:</span>
                    <span>JD ${total.toFixed(2)}</span>
                </div>
            `);

            $('#hidden_total_prices').val(subtotal);
            $('#hidden_total_discounts').val(totalDiscount);
        }

        function updateHiddenFields() {
            const productIds = Object.keys(selectedProducts);
            const productsDataArray = Object.values(selectedProducts).map(product => {
                const price = product.selling_price;
                const offerPrice = product.offer_price || price;
                const discount = price - offerPrice;
                return {
                    product_id: product.id,
                    quantity: product.quantity,
                    unit_price: price,
                    offer_price: offerPrice,
                    total_price: offerPrice * product.quantity,
                    discount_percentage: discount > 0 ? ((discount / price) * 100).toFixed(2) : 0,
                    discount_value: discount * product.quantity
                };
            });

            $('#hidden_products').val(JSON.stringify(productIds));
            $('#hidden_products_data').val(JSON.stringify(productsDataArray));
        }

        // ── Photo modal ──────────────────────────────────────────────

        let _modalPhotos = [];
        let _modalIndex  = 0;

        function viewProductPhotos(productId, event) {
            event.stopPropagation();

            const product = productsData.find(p => p.id === productId);
            if (!product) return;

            _modalPhotos = (product.photos && product.photos.length > 0)
                ? product.photos
                : [product.image];
            _modalIndex = 0;

            _renderModalPhoto();

            $('#imageModalLabel').text(product.name_en || product.name_ar);

            const multi = _modalPhotos.length > 1;
            $('#modalPrevBtn, #modalNextBtn, #photoCounter').toggle(multi);

            const modalEl = document.getElementById('imageModal');
            const existing = bootstrap.Modal.getInstance(modalEl);
            (existing || new bootstrap.Modal(modalEl)).show();
        }

        function changeModalPhoto(dir) {
            _modalIndex = (_modalIndex + dir + _modalPhotos.length) % _modalPhotos.length;
            _renderModalPhoto();
        }

        function _renderModalPhoto() {
            document.getElementById('modalMainImg').src = _modalPhotos[_modalIndex];
            $('#photoCounter').text((_modalIndex + 1) + ' / ' + _modalPhotos.length);
        }

        // Combine 12-hour time selection into 24-hour hidden field before submit
        function combineOrderTime() {
            let hour = parseInt($('#time_hour').val());
            const minute = '00';
            const period = $('#time_period').val();

            if (period === 'AM' && hour === 12) hour = 0;
            if (period === 'PM' && hour !== 12) hour += 12;

            $('#order_time').val(String(hour).padStart(2, '0') + ':' + minute + ':00');
        }

        // ── Pledge + submit ───────────────────────────────────────────
        function placeOrder() {
            if (!$('#pledge_checkbox').is(':checked')) {
                alert('{{ __('messages.You must pledge to return the character on the agreed day') }}');
                return false;
            }
            combineOrderTime();
            return true;
        }

        // ── Full-screen photo gallery (phone gallery style) ──────────
        let _allPhotos  = [];
        let _fsSwiper   = null;

        function buildGalleryFromProducts(products) {
            _allPhotos = [];
            products.forEach(function(p) {
                if (p.photos && p.photos.length) {
                    p.photos.forEach(function(ph) { _allPhotos.push(ph); });
                } else if (p.image) {
                    _allPhotos.push(p.image);
                }
            });
            if (!_allPhotos.length) return;

            // ── Build 3-thumbnail preview strip ──
            const strip = document.getElementById('gallery-thumbs');
            strip.innerHTML = '';
            const show = Math.min(3, _allPhotos.length);
            for (let i = 0; i < show; i++) {
                const thumb = document.createElement('div');
                thumb.className = 'gallery-thumb';
                const isLast = i === 2 && _allPhotos.length > 3;
                thumb.innerHTML = `<img src="${_allPhotos[i]}" loading="lazy" alt="">` +
                    (isLast ? `<div class="gallery-thumb-more">+${_allPhotos.length - 2}</div>` : '');
                strip.appendChild(thumb);
            }
            $('#gallery-section').show();

            // ── Build full-screen Swiper slides ──
            const fsSlides = document.getElementById('fs-slides');
            fsSlides.innerHTML = '';
            _allPhotos.forEach(function(src) {
                const s = document.createElement('div');
                s.className = 'swiper-slide';
                s.innerHTML = `<img src="${src}" loading="lazy" alt="">`;
                fsSlides.appendChild(s);
            });
        }

        function openFsGallery(startIndex) {
            if (!_allPhotos.length) return;
            startIndex = startIndex || 0;

            // Destroy old instance
            if (_fsSwiper) { _fsSwiper.destroy(true, true); _fsSwiper = null; }

            const total = _allPhotos.length;
            document.getElementById('fs-counter').textContent = (startIndex + 1) + ' / ' + total;

            _fsSwiper = new Swiper('#fsSwiper', {
                initialSlide: startIndex,
                loop: total > 1,
                grabCursor: true,
                keyboard: { enabled: true },
                navigation: {
                    nextEl: '#fs-gallery .swiper-button-next',
                    prevEl: '#fs-gallery .swiper-button-prev'
                },
                on: {
                    slideChange: function () {
                        document.getElementById('fs-counter').textContent =
                            (this.realIndex + 1) + ' / ' + total;
                    }
                }
            });

            const fs = document.getElementById('fs-gallery');
            fs.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeFsGallery() {
            document.getElementById('fs-gallery').style.display = 'none';
            document.body.style.overflow = '';
        }

        // Close on background tap (not on nav buttons)
        document.getElementById('fs-gallery').addEventListener('click', function(e) {
            if (e.target === this) closeFsGallery();
        });

        // Swipe up/down to close
        (function() {
            let startY = 0;
            const fs = document.getElementById('fs-gallery');
            fs.addEventListener('touchstart', function(e) { startY = e.touches[0].clientY; }, { passive: true });
            fs.addEventListener('touchend', function(e) {
                const diff = e.changedTouches[0].clientY - startY;
                if (Math.abs(diff) > 80) closeFsGallery();
            }, { passive: true });
        })();

    </script>
</body>

</html>
