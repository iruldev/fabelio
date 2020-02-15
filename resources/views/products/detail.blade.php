@extends('layouts.app')

@push('assetCSS')
<!-- select2 CSS -->
<link href="{{ env('APP_ASSET')('vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<!-- Toggles CSS -->
<link href="{{ env('APP_ASSET')('vendors/jquery-toggles/css/toggles.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ env('APP_ASSET')('vendors/jquery-toggles/css/themes/toggles-light.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@push('assetJS')
<!-- Tinymce JavaScript -->
<script src="{{ env('APP_ASSET')('vendors/tinymce/tinymce.min.js') }}"></script>
<!-- Tinymce Wysuhtml5 Init JavaScript -->
<script src="{{ env('APP_ASSET')('js/tinymce-data.js') }}"></script>
<!-- Select2 JavaScript -->
<script src="{{ env('APP_ASSET')('vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('js/select2-data.js') }}"></script>
<!-- Bootstrap Input spinner JavaScript -->
<script src="{{ env('APP_ASSET')('vendors/bootstrap-input-spinner/src/bootstrap-input-spinner.js') }}"></script>
<script src="{{ env('APP_ASSET')('js/inputspinner-data.js') }}"></script>
<!-- Toggles JavaScript -->
<script src="{{ env('APP_ASSET')('vendors/jquery-toggles/toggles.min.js') }}"></script>
<!-- Owl JavaScript -->
<script src="{{ env('APP_ASSET')('vendors/owl.carousel/dist/owl.carousel.min.js') }}"></script>
<!-- Owl Init JavaScript -->
<script src="{{ env('APP_ASSET')('js/owl-data.js') }}"></script>
@endpush

@push('script')
<script>
$(function() {
    let toggle = $('#promosi').val();
    let toggleOn = false;
    if (toggle == 1) {
        toggleOn = true
    }
    $('.toggle').toggles({
        drag: true, // allow dragging the toggle between positions
        click: true, // allow clicking on the toggle
        text: {
            on: 'ON', // text for the ON position
            off: 'OFF' // and off
        },
        on: toggleOn, // is the toggle ON on init
        animate: 250, // animation time (ms)
        easing: 'swing', // animation transition easing function
        checkbox: null, // the checkbox to toggle (for use in forms)
        clicker: null, // element that can be clicked on to toggle. removes binding from the toggle itself (use nesting)

        type: 'compact' // if this is set to 'select' then the select style toggle will be used
    });
})


</script>
@endpush

@section('content')
<section class="hk-sec-wrapper">
    <h6 class="hk-sec-title">Images <span class="text-danger">*</span></h6>
    <div  class="row">
        <div class="col-sm">
            <div class="row" id="imageThumb">
                @foreach ($images as $image)

                    <div class="col-sm-6 col-md-3 mt-15">
                        <img src="{{ env('S3_LINK') ? env('S3_LINK').$image->image_path : env('APP_ASSET')('storage/images/product/'.$image->image_path) }}" class="img-fluid rounded" alt="img">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-lg-7">
        <section class="hk-sec-wrapper">
            <div class="form-group">
                <label for="name">Product Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}"  required>
            </div>
            <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="4" required>{{ $product->description }}</textarea>
            </div>

            <div class="row">
                <div class="form-group col-lg-4">
                    <label for="category">Category <span class="text-danger">*</span></label>
                    <input type="text" id="category" name="category" class="form-control" value="{{ ucwords( str_replace('-',' ',$product->category) ) }}"  required>
                </div>
                <div class="form-group col-lg-4">
                    <label for="subcategory">Subcategory <span class="text-danger">*</span></label>
                    <input type="text" id="subcategory" name="subcategory" class="form-control" value="{{ ucwords( str_replace('-',' ',$product->subcategory) ) }}"  required>
                </div>
                <div class="form-group col-lg-4">
                    <label for="item">Item <span class="text-danger">*</span></label>
                    <input type="text" id="item" name="item" class="form-control" value="{{ ucwords( str_replace('-',' ',$product->item) ) }}"  required>
                </div>
            </div>
            <div class="form-group">
                <label for="display">Display At <span class="text-danger">*</span></label>
                <input type="text" id="display" name="display" class="form-control" value="{{ str_replace('-',' ',implode(', ' ,json_decode($product->display_at, true) ) ) }}"  required>
            </div>
        </section>
    </div>

    <div class="col-lg-5">
        <section class="hk-sec-wrapper">
            <div class="form-group">
                <label for="price">Price <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp.</span>
                    </div>
                    <input type="number" id="price" name="price" class="form-control" value="{{ $product->price }}" aria-label="Amount (to the nearest rupiah)" required>
                    <div class="input-group-append">
                        <span class="input-group-text">.00</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-5">
                    <label for="stock">Stock <span class="text-danger">*</span></label>
                    <input type="number" id="stock" name="stock" class="normal" min="0" step="10" value="{{ $product->stock }}" required />
                </div>
                <div class="form-group col-lg-4">
                    <label for="discount">Discount </label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="discount" name="discount" value="{{ $product->discount }}" aria-label="discount" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">%</span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <label for="promosi">Promosi </label>
                    <div class="toggle toggle-lg toggle-light"></div>
                    <input type="hidden" name="promosi" id="promosi"">
                </div>
            </div>
            <button onclick="selectThumb()" class="btn btn-primary btn-block mb-15">Thumbnail</button>
            <div class="form-group">
                <img class="img-fluid img-thumbnail" src="{{ env('S3_LINK') ? env('S3_LINK').$image->image_path : env('APP_ASSET')('storage/images/product/'.$thumb->image_path) }}" id="previewThumb" alt="img">
            </div>
        </section>
    </div>
</div>
<section class="hk-sec-wrapper">
    <h6 class="hk-sec-title">Specification <span class="text-danger">*</span></h6>
    <div  class="row">
        <div class="col-sm">
            <div class="tinymce-wrap">
                <textarea class="tinymce" name="specification">{{ $product->specification }}</textarea>
            </div>
        </div>
    </div>
</section>
@endsection
