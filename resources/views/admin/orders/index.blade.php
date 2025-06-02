@extends('layouts.admin')
@section('title')
    {{ __('messages.orders') }}
@endsection




@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.orders') }} </h3>
            <input type="hidden" id="token_search" value="{{ csrf_token() }}">

            <a href="{{ route('orders.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }}
                {{ __('messages.orders') }}</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
                   <form method="GET" action="{{ route('orders.index') }}">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>{{ __('messages.from_date') }}</label>
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('messages.to_date') }}</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('messages.Number') }}</label>
                                <input type="text" name="number" class="form-control" placeholder="Search #" value="{{ request('number') }}">
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('messages.User') }}</label>
                                <input type="text" name="user_name" class="form-control" placeholder="User Name" value="{{ request('user_name') }}">
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('messages.delivery_place') }}</label>
                                <select name="delivery_place" class="form-control">
                                    <option value="">{{ __('messages.choose') }}</option>
                                    @foreach ($deliveries as $delivery)
                                        <option value="{{ $delivery->place }}" {{ request('delivery_place') == $delivery->place ? 'selected' : '' }}>
                                            {{ $delivery->place }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-1">
                                <button type="submit" class="btn btn-primary w-100">{{ __('messages.Search') }}</button>
                                <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">{{ __('messages.reset') }}</a>
                            </div>
                        </div>
                    </form>



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
                                <th>{{ __('messages.Action') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>


                                        <td>{{ $info->number }}</td>
                                        <td>{{ $info->total_prices }}</td>
                                        <td>{{ $info->delivery_fee }}</td>
                                        <td>{{ $info->total_discounts }}</td>
                                        <td>
                                            @if ($info->order_status == 1)
                                                Pending
                                            @elseif($info->order_status == 2)
                                                OnTheWay
                                            @elseif($info->order_status == 3)
                                                Cancelled
                                            @elseif($info->order_status == 4)
                                                Failed
                                            @else
                                                DELIVERD
                                            @endif
                                        </td>
                                        <td>{{ $info->user->name }}</td>
                                        <td>{{ $info->delivery->place ?? null }}</td>
                                        <td>{{ $info->date }}</td>

                                        <td>
                                            @if ($info->order_status != 6 && $info->order_status != 3)
                                                @can('order-edit')
                                                    <a href="{{ route('orders.edit', $info->id) }}"
                                                        class="btn btn-sm btn-primary">{{ __('messages.Edit') }}</a>
                                                @endcan
                                            @endif

                                             @can('order-table')
                                                    <a href="{{ route('orders.show', $info->id) }}"
                                                        class="btn btn-sm btn-secondary">{{ __('messages.Show') }}</a>
                                             @endcan

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                       {{ $data->appends(request()->query())->links() }}

                    @else
                        <div class="alert alert-danger">
                            {{ __('messages.No_data') }} </div>
                </div>
                @endif
            @endcan
        </div>



    </div>

    </div>

    </div>

@endsection

@section('script')
@endsection
