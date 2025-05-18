@extends('layouts.admin')
@section('title')
{{ __('messages.orders') }}
@endsection




@section('content')


      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> {{ __('messages.orders') }} </h3>
          <input type="hidden" id="token_search" value="{{csrf_token() }}">

          <a href="{{ route('orders.create') }}" class="btn btn-sm btn-success" > {{ __('messages.New') }} {{ __('messages.orders') }}</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
          <div class="col-md-4">

                      </div>

                          </div>
               <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            @can('order-table')
            @if (isset($data) && !$data->isEmpty())
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th>{{ __('messages.number') }}</th>
                    <th>{{ __('messages.total_prices') }}</th>
                    <th>{{ __('messages.total_discounts') }}</th>
                    <th>{{ __('messages.order_status') }}</th>
                    <th>{{ __('messages.user') }}</th>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.Action') }}</th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>


                        <td>{{ $info->number }}</td>
                        <td>{{ $info->total_prices }}</td>
                        <td>{{ $info->total_discounts }}</td>
                        <td>@if($info->order_status==1) Pending @elseif($info->order_status==2) OnTheWay @elseif($info->order_status==3) Cancelled @elseif($info->order_status==4) Failed @else DELIVERD @endif</td>
                        <td>{{ $info->user->name }}</td>
                        <td>{{ $info->date }}</td>

                        <td>
                            @can('order-edit')
                            <a href="{{ route('orders.edit', $info->id) }}" class="btn btn-sm btn-primary">{{ __('messages.Edit') }}</a>
                            @endcan
                            @can('order-delete')
                            <form action="{{ route('orders.destroy', $info->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.Delete') }}</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            {{ $data->links() }}

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


