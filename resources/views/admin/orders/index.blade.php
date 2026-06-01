@extends('layouts.admin')
@section('title')
    {{ __('messages.orders') }}
@endsection

@section('css')
<style>
.conflict-product { color: #dc3545; font-weight: 600; }
.conflict-badge { font-size: .7rem; }
.today-conflict-row { border-right: 4px solid #dc3545 !important; background: #fff5f5 !important; }
.today-conflict-bar {
    background: #dc3545; color: #fff; font-size: 0.78rem; font-weight: 700;
    padding: 3px 10px; border-radius: 4px; display: inline-block;
    animation: blink-alert 1.2s step-start infinite;
}
@keyframes blink-alert { 50% { opacity: 0.4; } }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.orders') }} </h3>
            <input type="hidden" id="token_search" value="{{ csrf_token() }}">
            <a href="{{ route('orders.create') }}" class="btn btn-sm btn-success">
                {{ __('messages.New') }} {{ __('messages.orders') }}
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('orders.index') }}">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label>{{ __('messages.from_date') }}</label>
                        <input type="date" name="from_date" class="form-control"
                            value="{{ request('from_date', $filters['from_date'] ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label>{{ __('messages.to_date') }}</label>
                        <input type="date" name="to_date" class="form-control"
                            value="{{ request('to_date', $filters['to_date'] ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label>{{ __('messages.Number') }}</label>
                        <input type="text" name="number" class="form-control" placeholder="Search #"
                            value="{{ request('number', $filters['number'] ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label>{{ __('messages.User') }}</label>
                        <input type="text" name="user_name" class="form-control" placeholder="User Name"
                            value="{{ request('user_name', $filters['user_name'] ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label>{{ __('messages.delivery_place') }}</label>
                        <select name="delivery_place" class="form-control">
                            <option value="">{{ __('messages.choose') }}</option>
                            @foreach ($deliveries as $delivery)
                                <option value="{{ $delivery->place }}"
                                    {{ request('delivery_place', $filters['delivery_place'] ?? '') == $delivery->place ? 'selected' : '' }}>
                                    {{ $delivery->place }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-primary w-100">{{ __('messages.Search') }}</button>
                        <a href="{{ route('orders.index', ['reset' => 1]) }}" class="btn btn-secondary w-100">
                            {{ __('messages.reset') }}
                        </a>
                    </div>
                </div>
            </form>

            <div class="mb-3">
                <a href="{{ route('orders.export', request()->query()) }}"
                   class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> {{ __('messages.Export Excel') }}
                </a>
            </div>

            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('order-table')
                    @if (isset($data) && !$data->isEmpty())
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.number') }}</th>
                                <th>{{ __('messages.total_prices') }}</th>
                                <th>{{ __('messages.delivery_fee') }}</th>
                                <th>{{ __('messages.total_discounts') }}</th>
                                <th>{{ __('messages.order_status') }}</th>
                                <th>{{ __('messages.user') }}</th>
                                <th>{{ __('messages.delivery') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.Phone') }}</th>
                                <th>{{ __('messages.products') }}</th>
                                <th>{{ __('messages.Action') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    @php
                                        $hasConflict = $info->orderProducts->pluck('product_id')
                                            ->intersect($conflictedProductIds)->isNotEmpty();
                                        $isTodayConflict = in_array($info->id, $todayConflictOrderIds);
                                    @endphp
                                    <tr class="{{ $isTodayConflict ? 'today-conflict-row' : ($hasConflict ? '' : '') }}"
                                        @if($hasConflict && !$isTodayConflict) style="border-right: 3px solid #ffc107;" @endif>
                                        <td>{{ $info->number }}</td>
                                        <td>{{ $info->total_prices }}</td>
                                        <td>{{ $info->delivery_fee }}</td>
                                        <td>{{ $info->total_discounts }}</td>
                                        <td>
                                            @if ($info->order_status == 1)
                                                <span class="badge badge-warning">{{ __('messages.Pending') }}</span>
                                            @elseif($info->order_status == 2)
                                                <span class="badge badge-info">{{ __('messages.Processing') }}</span>
                                            @elseif($info->order_status == 3)
                                                <span class="badge badge-danger">{{ __('messages.Cancelled') }}</span>
                                            @elseif($info->order_status == 4)
                                                <span class="badge badge-secondary">{{ __('messages.failed') }}</span>
                                            @elseif($info->order_status == 6)
                                                <span class="badge badge-success">{{ __('messages.Executed') }}</span>
                                            @elseif($info->order_status == 7)
                                                <span class="badge badge-primary">{{ __('messages.Returned') }}</span>
                                            @else
                                                <span class="badge badge-light">{{ $info->order_status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $info->user->name }}</td>
                                        <td>{{ $info->delivery->place ?? null }}<br>{{ $info->address }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($info->date)->format('d/m/Y') }}<br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($info->date)->format('g:i') }}
                                                {{ \Carbon\Carbon::parse($info->date)->format('A') === 'AM' ? 'ص' : 'م' }}
                                            </small>
                                            @if($info->end_date)
                                                <br><small class="text-danger">
                                                    ← {{ \Carbon\Carbon::parse($info->end_date)->format('d/m/Y') }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $info->user->phone ?? '-' }}</td>
                                        <td>
                                            @if($isTodayConflict)
                                                <span class="today-conflict-bar mb-1 d-block">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ __('messages.Character not returned - urgent') }}
                                                </span>
                                            @endif
                                            @foreach($info->orderProducts as $item)
                                                @php $isConflict = in_array($item->product_id, $conflictedProductIds); @endphp
                                                <small @if($isConflict) class="conflict-product" title="{{ __('messages.Conflict Warning') }}" @endif>
                                                    @if($isConflict)<i class="fas fa-exclamation-triangle"></i> @endif
                                                    {{ $item->product->name_ar }} ({{ $item->quantity }})
                                                </small><br>
                                            @endforeach
                                        </td>
                                        <td style="min-width:160px;">
                                            {{-- Quick action buttons --}}
                                            @if(in_array($info->order_status, [1, 2]))
                                                <button class="btn btn-xs btn-success mb-1 btn-quick-status"
                                                    data-id="{{ $info->id }}" data-status="6"
                                                    title="{{ __('messages.Mark as Executed') }}">
                                                    <i class="fas fa-check"></i> {{ __('messages.Executed') }}
                                                </button>
                                                <button class="btn btn-xs btn-danger mb-1 btn-quick-status"
                                                    data-id="{{ $info->id }}" data-status="3"
                                                    title="{{ __('messages.Cancel') }}">
                                                    <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                                                </button>
                                            @endif

                                            @if($info->order_status == 6)
                                                <button class="btn btn-xs btn-primary mb-1 btn-quick-status"
                                                    data-id="{{ $info->id }}" data-status="7"
                                                    title="{{ __('messages.Mark as Returned') }}">
                                                    <i class="fas fa-undo"></i> {{ __('messages.Returned') }}
                                                </button>
                                            @endif

                                            {{-- Edit / Show --}}
                                            @if (!in_array($info->order_status, [3, 7]))
                                                @can('order-edit')
                                                    <a href="{{ route('orders.edit', $info->id) }}"
                                                        class="btn btn-sm btn-warning mb-1">{{ __('messages.Edit') }}</a>
                                                @endcan
                                            @endif

                                            @can('order-table')
                                                <a href="{{ route('orders.show', $info->id) }}"
                                                    class="btn btn-sm btn-secondary mb-1">{{ __('messages.Show') }}</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $data->appends(request()->query())->links() }}
                    @else
                        <div class="alert alert-danger">{{ __('messages.No_data') }}</div>
                    @endif
                @endcan
            </div>
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

        var labels = { 6: '{{ __("messages.Executed") }}', 3: '{{ __("messages.Cancelled") }}', 7: '{{ __("messages.Returned") }}' };
        if (!confirm('{{ __("messages.Confirm action") }}: ' + labels[status] + ' ?')) return;

        btn.prop('disabled', true);
        $.ajax({
            url: '{{ url("") }}/{{ LaravelLocalization::getCurrentLocale() }}/admin/orders/' + id + '/quick-status',
            method: 'PATCH',
            data: { _token: '{{ csrf_token() }}', status: status },
            success: function (res) {
                if (res.success) {
                    location.reload();
                }
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || '{{ __("messages.Error creating order: ") }}');
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection
