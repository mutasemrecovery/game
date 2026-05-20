@extends('layouts.admin')
@section('title')
    {{ __('messages.Pending Delivery Orders') }}
@endsection

@section('css')
<style>
.overdue-row  { border-right: 4px solid #dc3545 !important; background: #fff5f5; }
.today-row    { border-right: 4px solid #fd7e14 !important; background: #fff9f0; }
.future-row   { border-right: 4px solid #28a745 !important; }
.date-badge   { font-size: .78rem; font-weight: 600; padding: 2px 7px; border-radius: 10px; }
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

        {{-- Single date filter --}}
        <form method="GET" action="{{ route('orders.pending-delivery') }}" class="mb-3">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label>{{ __('messages.date') }}</label>
                    <input type="date" name="check_date" class="form-control" value="{{ $checkDate }}">
                    <small class="text-muted">{{ __('messages.Shows orders up to this date') }}</small>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">{{ __('messages.Search') }}</button>
                    <a href="{{ route('orders.pending-delivery') }}" class="btn btn-secondary">{{ __('messages.reset') }}</a>
                </div>
            </div>
        </form>

        {{-- Legend --}}
        <div class="mb-3 d-flex gap-3 flex-wrap" style="gap:.6rem;display:flex;">
            <span><span class="date-badge" style="background:#ffc9c9;color:#c92a2a;">{{ __('messages.Overdue') }}</span> {{ __('messages.Party date passed') }}</span>
            <span><span class="date-badge" style="background:#ffd8a8;color:#e67700;">{{ __('messages.Today') }}</span> {{ __('messages.Party is today') }}</span>
            <span><span class="date-badge" style="background:#b2f2bb;color:#2f9e44;">{{ __('messages.Upcoming') }}</span> {{ __('messages.Future party') }}</span>
        </div>

        @if($data->isEmpty())
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i> {{ __('messages.No pending delivery orders') }}
            </div>
        @else
        <div class="table-responsive">
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
            <tbody>
                @foreach($data as $order)
                    @php
                        $orderDate = \Carbon\Carbon::parse($order->date)->startOfDay();
                        $rowClass  = $orderDate->lt($today) ? 'overdue-row'
                                   : ($orderDate->eq($today)  ? 'today-row' : 'future-row');
                        $dateBadgeStyle = $orderDate->lt($today)
                            ? 'background:#ffc9c9;color:#c92a2a;'
                            : ($orderDate->eq($today) ? 'background:#ffd8a8;color:#e67700;' : 'background:#b2f2bb;color:#2f9e44;');
                        $dateLabel = $orderDate->lt($today) ? __('messages.Overdue')
                                   : ($orderDate->eq($today)  ? __('messages.Today') : __('messages.Upcoming'));
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $order->number }}</td>
                        <td>
                            <span class="date-badge" style="{{ $dateBadgeStyle }}">{{ $dateLabel }}</span><br>
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
            </tbody>
        </table>
        </div>
        <br>{{ $data->appends(request()->query())->links() }}
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
            success: function (res) { if (res.success) location.reload(); },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Error');
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection
