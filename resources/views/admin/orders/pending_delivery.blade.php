@extends('layouts.admin')
@section('title')
    {{ __('messages.Pending Delivery Orders') }}
@endsection

@section('css')
<style>
.product-pill { display:inline-block; background:#e9ecef; border-radius:20px;
                padding:1px 8px; font-size:.78rem; margin:1px; }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h3 class="card-title mb-0">
            <i class="fas fa-clock text-warning mr-2"></i>
            {{ __('messages.Pending Delivery Orders') }}
            <span class="badge badge-warning ml-2">{{ $data->total() }}</span>
        </h3>
        <a href="{{ route('orders.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus mr-1"></i> {{ __('messages.New') }} {{ __('messages.orders') }}
        </a>
    </div>

    <div class="card-body">

        <form method="GET" action="{{ route('orders.pending-delivery') }}" class="mb-3">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label>{{ __('messages.date') }}</label>
                    <input type="date" name="check_date" class="form-control" value="{{ $checkDate }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">{{ __('messages.Search') }}</button>
                    <a href="{{ route('orders.pending-delivery') }}" class="btn btn-secondary">{{ __('messages.reset') }}</a>
                </div>
            </div>
        </form>

        @if($data->isEmpty() && $executedData->isEmpty() && $cancelledData->isEmpty())
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i> {{ __('messages.No pending delivery orders') }}
            </div>
        @else
        <div style="overflow-x:auto;">
        <table class="table table-bordered table-hover">
            <thead class="custom_thead">
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.user') }}</th>
                    <th>{{ __('messages.Phone') }}</th>
                    <th>{{ __('messages.products') }}</th>
                    <th>{{ __('messages.delivery') }}</th>
                    <th>{{ __('messages.Note') }}</th>
                    <th>{{ __('messages.order_status') }}</th>
                    <th>{{ __('messages.Action') }}</th>
                </tr>
            </thead>
            <tbody id="pending-tbody">
                @foreach($data as $order)
                    <tr>
                        <td>{{ $order->number }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($order->date)->format('g:i') }}
                                {{ \Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م' }}
                            </small>
                            @if($order->end_date)
                                <br><small class="text-danger">← {{ \Carbon\Carbon::parse($order->end_date)->format('d/m/Y') }}</small>
                            @endif
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
                        <td>
                            @if($order->order_status == 1)
                                <span class="badge badge-warning">{{ __('messages.Pending') }}</span>
                            @else
                                <span class="badge badge-info">{{ __('messages.Processing') }}</span>
                            @endif
                        </td>
                        <td style="min-width:150px;">
                            <button class="btn btn-xs btn-success mb-1 btn-quick-status"
                                data-id="{{ $order->id }}" data-status="6">
                                <i class="fas fa-check mr-1"></i>{{ __('messages.Executed') }}
                            </button>
                            <button class="btn btn-xs btn-danger mb-1 btn-quick-status"
                                data-id="{{ $order->id }}" data-status="3">
                                <i class="fas fa-times mr-1"></i>{{ __('messages.Cancel') }}
                            </button>
                            @can('order-edit')
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-xs btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i>{{ __('messages.Edit') }}
                            </a>
                            @endcan
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-xs btn-secondary mb-1">
                                <i class="fas fa-eye mr-1"></i>{{ __('messages.Show') }}
                            </a>
                        </td>
                    </tr>
                @endforeach

                @foreach($executedData as $order)
                    <tr style="background:#d4edda; border-right:4px solid #28a745;">
                        <td>{{ $order->number }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($order->date)->format('g:i') }}
                                {{ \Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م' }}
                            </small>
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
                        <td><span class="badge badge-success">{{ __('messages.Executed') }}</span></td>
                        <td style="min-width:100px;">
                            @can('order-edit')
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-xs btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i>{{ __('messages.Edit') }}
                            </a>
                            @endcan
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-xs btn-secondary mb-1">
                                <i class="fas fa-eye mr-1"></i>{{ __('messages.Show') }}
                            </a>
                        </td>
                    </tr>
                @endforeach

                @foreach($cancelledData as $order)
                    <tr style="background:#f8d7da; border-right:4px solid #dc3545;">
                        <td>{{ $order->number }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($order->date)->format('g:i') }}
                                {{ \Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م' }}
                            </small>
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
                        <td><span class="badge badge-danger">{{ __('messages.Cancelled') }}</span></td>
                        <td style="min-width:100px;">
                            @can('order-edit')
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-xs btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i>{{ __('messages.Edit') }}
                            </a>
                            @endcan
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-xs btn-secondary mb-1">
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
    var labels = {
        6: '{{ __("messages.Executed") }}',
        3: '{{ __("messages.Cancelled") }}'
    };

    $(document).on('click', '.btn-quick-status', function () {
        var btn    = $(this);
        var id     = btn.data('id');
        var status = btn.data('status');
        if (!confirm('{{ __("messages.Confirm action") }}: ' + labels[status] + '?')) return;

        btn.prop('disabled', true);
        $.ajax({
            url: '{{ url("") }}/{{ LaravelLocalization::getCurrentLocale() }}/admin/orders/' + id + '/quick-status',
            method: 'PATCH',
            data: { _token: '{{ csrf_token() }}', status: status },
            success: function (res) {
                if (!res.success) return;
                if (status == 6) {
                    var row = btn.closest('tr');
                    row.find('td').eq(7).html('<span class="badge badge-success">{{ __("messages.Executed") }}</span>');
                    row.find('.btn-quick-status').remove();
                    row.css({ 'background': '#d4edda', 'border-right': '4px solid #28a745', 'transition': 'background 0.4s' });
                    row.appendTo('#pending-tbody');
                } else if (status == 3) {
                    var row = btn.closest('tr');
                    row.find('td').eq(7).html('<span class="badge badge-danger">{{ __("messages.Cancelled") }}</span>');
                    row.find('.btn-quick-status').remove();
                    row.css({ 'background': '#f8d7da', 'border-right': '4px solid #dc3545', 'transition': 'background 0.4s' });
                    row.appendTo('#pending-tbody');
                }
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
