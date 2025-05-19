@extends('layouts.admin')
@section('title')

{{ __('messages.Edit') }} {{ __('messages.products') }}
@endsection



@section('contentheaderlink')
<a href="{{ route('products.index') }}">  {{ __('messages.products') }} </a>
@endsection

@section('contentheaderactive')
{{ __('messages.Edit') }}
@endsection


@section('css')
<style>
    /* Style for the "plus" button */
    #add-variation {
        display: block;
        margin-top: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    /* Style for the variation fields container */
    #variationFields {
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 10px;
    }

    /* Style for individual variation fields */
    .variation {
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 10px;
    }
</style>
@endsection


@section('content')

      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.products') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


      <form action="{{ route('products.update',$data['id']) }}" method="post" enctype='multipart/form-data'>
        <div class="row">
        @csrf
        @method('PUT')


        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('messages.Number') }}</label>
                <input name="number" id="number" class="form-control"
                    value="{{ old('number', $data['number']) }}">
                @error('number')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('messages.Name_en') }}</label>
                <input name="name_en" id="name_en" class="form-control"
                    value="{{ old('name_en', $data['name_en']) }}">
                @error('name_en')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('messages.Name_ar') }}</label>
                <input name="name_ar" id="name_ar" class="form-control"
                    value="{{ old('name_ar', $data['name_ar']) }}">
                @error('name_ar')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('messages.description_en') }}</label>
                <textarea name="description_en" id="description_en" class="form-control"
                    value="{{ old('description_en', $data['description_en']) }}">{{$data['description_en']}}</textarea>
                @error('description_en')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('messages.description_ar') }}</label>
                <textarea name="description_ar" id="description_ar" class="form-control"
                    value="{{ old('description_ar', $data['description_ar']) }}">{{$data['description_ar']}}</textarea>
                @error('description_ar')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>


      

      



        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('messages.Selling_price') }}</label>
                <input name="selling_price" id="selling_price" class="form-control"
                    value="{{ old('selling_price', $data->selling_price) }}">
                @error('selling_price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label> {{ __('messages.Status') }}</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Select</option>
                    <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>


      

        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('messages.Current_Images') }}</label>
                <div class="row">
                    @if($data->productImages->count() > 0)
                        @foreach($data->productImages as $image)
                            <div class="col-md-3 mb-2">
                                <div class="image-container" style="position: relative; display: inline-block;">
                                    <img src="{{ asset('assets/admin/uploads/' . $image->photo) }}" alt="Product Image" height="100px" width="100px" style="margin-bottom: 5px;">
                                    <div class="form-check">
                                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" id="img{{ $image->id }}" class="form-check-input">
                                        <label class="form-check-label" for="img{{ $image->id }}">{{ __('messages.Remove') }}</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No images available for this product.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Product Images</label>
                <input type="file" name="photo[]" class="form-control" multiple>
            </div>
        </div>

      <div class="col-md-12">
      <div class="form-group text-center">
        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Update') }}</button>
        <a href="{{ route('products.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

      </div>
    </div>

  </div>
            </form>



            </div>




        </div>
      </div>






@endsection



@section('script')



<script>
    $(function() {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            localStorage.setItem('lastTab', $(this).attr('href'));
        });

        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('[href="' + lastTab + '"]').tab('show');
        } else {
            $('a[data-toggle="tab"]').first().tab('show');
        }
    });
</script>
@endsection




