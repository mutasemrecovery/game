@extends('layouts.admin')
@section('title')
    {{ __('messages.Out Not Returned Orders') }}
@endsection

@section('css')
<style>
.days-badge  { font-size:.78rem; font-weight:700; padding:2px 8px; border-radius:10px; }
.product-pill{ display:inline-block; background:#e9ecef; border-radius:20px;
               padding:1px 8px; font-size:.78rem; margin:1px; }
.urgent-row  { border-right: 4px solid #dc3545 !important; background:#fff5f5; }
.normal-row  { border-right: 4px solid #fd7e14 !important; }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h3 class="card-title mb-0">
            <i class="fas fa-undo text-danger mr-2"></i>
            {{ __('messages.Out Not Returned Orders') }}
            <span class="badge badge-danger ml-2">{{ $data->total() }}</span>
        </h3>
    </div>

    <div class="card-body">

        <form method="GET" action="{{ route('orders.out-not-returned') }}" class="mb-3">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label>{{ __('messages.date') }}</label>
                    <input type="date" name="check_date" class="form-control" value="{{ $checkDate }}">
                    <small class="text-muted">{{ __('messages.Shows executed orders before this date') }}</small>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">{{ __('messages.Search') }}</button>
                    <a href="{{ route('orders.out-not-returned') }}" class="btn btn-secondary">{{ __('messages.reset') }}</a>
                </div>
            </div>
        </form>

        @if($data->isEmpty() && $returnedData->isEmpty())
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i> {{ __('messages.No unreturned orders') }}
            </div>
        @else
        <div style="overflow-x:auto;">
        <table class="table table-bordered table-hover">
            <thead class="custom_thead">
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.Days Out') }}</th>
                    <th>{{ __('messages.user') }}</th>
                    <th>{{ __('messages.Phone') }}</th>
                    <th>{{ __('messages.products') }}</th>
                    <th>{{ __('messages.delivery') }}</th>
                    <th>{{ __('messages.Note') }}</th>
                    <th>{{ __('messages.Action') }}</th>
                </tr>
            </thead>
            <tbody id="out-tbody">
                @foreach($data as $order)
                    @php
                        $daysOut    = \Carbon\Carbon::parse($order->date)->diffInDays($today, false);
                        $rowClass   = $daysOut >= 2 ? 'urgent-row' : 'normal-row';
                        $badgeColor = $daysOut >= 2
                            ? 'background:#ffc9c9;color:#c92a2a;'
                            : 'background:#ffd8a8;color:#e67700;';
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $order->number }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</strong>
                            @if($order->end_date)
                                <br><small class="text-danger">← {{ \Carbon\Carbon::parse($order->end_date)->format('d/m/Y') }}</small>
                            @endif<br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($order->date)->format('g:i') }}
                                {{ \Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م' }}
                            </small>
                        </td>
                        <td>
                            <span class="days-badge" style="{{ $badgeColor }}">
                                {{ $daysOut }} {{ __('messages.days') }}
                            </span>
                        </td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td>{{ $order->user->phone ?? '-' }}</td>
                        <td>
                            @foreach($order->orderProducts as $item)
                                <span class="product-pill">{{ $item->product->name_ar }} ({{ $item->quantity }})</span>
                            @endforeach
                        </td>
                        <td>{{ $order->delivery->place ?? '-' }}<br><small>{{ $order->address }}</small></td>
                        <td><small>{{ $order->note ?? '-' }}</small></td>
                        <td style="min-width:130px;">
                            <button class="btn btn-sm btn-primary mb-1 btn-quick-status"
                                data-id="{{ $order->id }}" data-status="7">
                                <i class="fas fa-undo mr-1"></i>{{ __('messages.Returned') }}
                            </button>
                            @can('order-edit')
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i>{{ __('messages.Edit') }}
                            </a>
                            @endcan
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-secondary mb-1">
                                <i class="fas fa-eye mr-1"></i>{{ __('messages.Show') }}
                            </a>
                        </td>
                    </tr>
                @endforeach

                @foreach($returnedData as $order)
                    <tr style="background:#d4edda; border-right:4px solid #28a745;">
                        <td>{{ $order->number }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($order->date)->format('g:i') }}
                                {{ \Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م' }}
                            </small>
                        </td>
                        <td>
                            <span class="days-badge" style="background:#b2f2bb;color:#2f9e44;">
                                {{ __('messages.Returned') }}
                            </span>
                        </td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td>{{ $order->user->phone ?? '-' }}</td>
                        <td>
                            @foreach($order->orderProducts as $item)
                                <span class="product-pill">{{ $item->product->name_ar }} ({{ $item->quantity }})</span>
                            @endforeach
                        </td>
                        <td>{{ $order->delivery->place ?? '-' }}<br><small>{{ $order->address }}</small></td>
                        <td><small>{{ $order->note ?? '-' }}</small></td>
                        <td style="min-width:100px;">
                            @can('order-edit')
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i>{{ __('messages.Edit') }}
                            </a>
                            @endcan
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-secondary mb-1">
                                <i class="fas fa-eye mr-1"></i>{{ __('messages.Show') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @if($data->isNotEmpty())
            <br>{{ $data->appends(request()->query())->links() }}
        @endif
        @endif

    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {
    $(document).on('click', '.btn-quick-status', function () {
        var btn    = $(this);
        var id     = btn.data('id');
        var status = btn.data('status');
        if (!confirm('{{ __("messages.Confirm action") }}: {{ __("messages.Returned") }}?')) return;

        btn.prop('disabled', true);
        $.ajax({
            url: '{{ url("") }}/{{ LaravelLocalization::getCurrentLocale() }}/admin/orders/' + id + '/quick-status',
            method: 'PATCH',
            data: { _token: '{{ csrf_token() }}', status: status },
            success: function (res) {
                if (!res.success) return;
                var row = btn.closest('tr');
                row.removeClass('urgent-row normal-row');
                row.find('.days-badge').css({ 'background': '#b2f2bb', 'color': '#2f9e44' })
                   .text('{{ __("messages.Returned") }}');
                row.find('.btn-quick-status').remove();
                row.css({ 'background': '#d4edda', 'border-right': '4px solid #28a745', 'transition': 'background 0.4s' });
                row.appendTo('#out-tbody');
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Error');
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection
