@extends('layouts.app')

@push('assetCSS')
<!-- Bootstrap Dropzone CSS -->
<link href="{{ env('APP_ASSET')('vendors/dropzone/dist/dropzone.css') }}" rel="stylesheet" type="text/css"/>
<!-- select2 CSS -->
<link href="{{ env('APP_ASSET')('vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<!-- Toggles CSS -->
<link href="{{ env('APP_ASSET')('vendors/jquery-toggles/css/toggles.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ env('APP_ASSET')('vendors/jquery-toggles/css/themes/toggles-light.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@push('assetJS')
<!-- Dropzone JavaScript -->
<script src="{{ env('APP_ASSET')('vendors/dropzone/dist/dropzone.js') }}"></script>
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
{{-- <script src="{{ env('APP_ASSET')('js/toggle-data.js') }}"></script> --}}
@endpush

@push('script')
<script>
$('.toggle').toggles({
    drag: true, // allow dragging the toggle between positions
    click: true, // allow clicking on the toggle
    text: {
        on: 'ON', // text for the ON position
        off: 'OFF' // and off
    },
    on: false, // is the toggle ON on init
    animate: 250, // animation time (ms)
    easing: 'swing', // animation transition easing function
    checkbox: null, // the checkbox to toggle (for use in forms)
    clicker: null, // element that can be clicked on to toggle. removes binding from the toggle itself (use nesting)

    type: 'compact' // if this is set to 'select' then the select style toggle will be used
});

$('.toggle').on('toggle', function(e, active) {
    if (active) {
        $('#promosi').val(1);
    } else {
        $('#promosi').val(0);
    }
});

let imageList = new Array;
let imageId = []
let i = 0;
Dropzone.options.dropzonewidget = {
    url: "upload-image",
    addRemoveLinks: false,
    acceptedFiles: 'image/*',
    maxFilesize: 20,
    init: function () {
        this.on("success", function (file, response) {
            imageId.push(response.image_id)
            imageList[i] = {
                "imagePath": response.image_path,
                "imageId": response.image_id,
            };
            $('#image_id').val(imageId);
            i++;
        });
    }
}

convertToSlug = Text =>
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}


const categorys = {
    'ruang-tamu' : {
        'sofa' : [
            'Kursi Armchair',
            'Sofa 2 Seater',
            'Sofa 3 Seater',
            'Sofa L',
            'Sofa Bed',
            'Set Kursi Tamu',
            'Promo Sofa',
            'Semua Sofa'
        ],
        'tempat-duduk' : [
            'Recliners',
            'Bangku',
            'Kursi Santai',
            'Bean Bag',
            'Promo Tempat Duduk',
            'Semua Tempat Duduk'
        ],
        'tempat-pengimpanan' : [
            'Meja Tamu',
            'Meja Samping',
            'Meja TV',
            'Kabinet',
            'Rak Sepatu',
            'Rak',
            'Promo Tempat Penyimpanan',
            'Semua Tempat Penyimpanan'
        ]
    },

    'ruang-makan' : {
        'kursi' : [
            'Kursi Makan',
            'Bangku',
            'Promo Kursi dan Bangku',
            'Semua Kursi'
        ],
        'meja-makan' : [
            '4 Seater Meja Makan',
            '6 Seater Meja Makan',
            '8 Seater Meja Makan'
        ],
        'tempat-penyimpanan' : [
            'Kabinet',
            'Rak',
            'Semua Tempat Penyimpanan'
        ]
    },

    'kamar-tidur' : {
        'tempat-tidur' : [
            'Set Kamar Tidur',
            'Bantal Tidur',
            'Semua Tempat Tidur'
        ],
        'kasur' : [
            'Kasur Set',
            'Kasur Latex Cloud',
            'Kasur Pocket Spring Hybrid',
            'Kasur Memory Plush',
            'Semua Kasur'
      ]
    }
}


// Get Category
getSubcategory = (value) => {
    $('#subcategory option').remove();
    $('#item option').remove();

    $('#subcategory').append(`<option>Select</option>`);
    $.each(categorys[value], key => {
        let text = key.replace('-', ' ');
        text = text.toLowerCase().replace(/\b[a-z]/g, letter => {return letter.toUpperCase()});
        $('#subcategory').append(`<option value="${key}">${text}</option>`);
    })

}

getItem = (value) => {
    $('#item option').remove();
    let category = $('#category').val();

    $('#item').append(`<option>Select</option>`);
    $.each(categorys[category][value], (_, index) => {
        let text = index.toLowerCase();
        let value = convertToSlug(text);
        text = text.toLowerCase().replace(/\b[a-z]/g, letter => {return letter.toUpperCase()});
        $('#item').append(`<option value="${value}">${text}</option>`);
    })

}

selectThumb = () => {
    event.preventDefault();

    let imageIds = $('#image_id').val();

    if (imageIds.length == 0) {
        $('#dropzonewidget').click();
        return
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        method: "POST",
        url: "{{ route('ajax.get.image') }}",
        data: { imageIds : imageIds }
    }).done(function( response ) {
        $('#imageThumb div').remove();
        $.each(response, (_, res) => {
            $('#imageThumb').append(`
                <div class="col-sm-6 col-md-3 mt-15">
                    <a href="javascript:void(0);" onclick="setThumb('${res.id}', '${res.image_path}')">
                        <img src="{{ env('APP_ASSET')('storage/images/product/${res.image_path}') }}" class="img-fluid rounded" alt="img">
                    </a>
                </div>
            `);
        })
        $('#modalThumb').modal('show');
    });
}

setThumb = (id, src) => {
    $('#thumb_id').val(id);
    $('#previewThumb').attr('src', `{{ env('APP_ASSET')('storage/images/product/${src}') }}`);
    $('#previewThumb').removeAttr('hidden');
    $('#modalThumb').modal('hide');
}
</script>
@endpush

@section('content')
<section class="hk-sec-wrapper">
    <h6 class="hk-sec-title">Upload Image <span class="text-danger">*</span></h6>
    <div  class="row">
        <div class="col-sm">
            <form action="/donee_doc_upload" class="dropzone" id="dropzonewidget" method="POST" enctype="multipart/form-data">
                @csrf

                <input hidden name="images[]" id="imagesUploaded" type="text" />
            </form>
        </div>
    </div>
</section>

<form action="{{ route('product.store') }}" method="post">
    @csrf
    <button type="submit" class="btn btn-success btn-block mb-15">Save New Product</button>
    <div class="row">
        <div class="col-lg-7">
            <section class="hk-sec-wrapper">
                <div class="form-group">
                    <label for="name">Product Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="row">
                    <div class="form-group col-lg-4">
                        <label for="category">Category <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="category" name="category" onchange="getSubcategory(this.value)" required>
                            <option>Select</option>
                            <option value="ruang-tamu">Ruang Tamu</option>
                            <option value="ruang-makan">Ruang Makan</option>
                            <option value="kamar-tidur">Kamar Tidur</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="subcategory">Subcategory <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="subcategory" name="subcategory" onchange="getItem(this.value)" required>
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="item">Item <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="item" name="item" required>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="display">Display At <span class="text-danger">*</span></label>
                    <select class="select2 select2-multiple" id="display" name="display[]" multiple="multiple" data-placeholder="Choose" required>
                        <option value="Panglima-Polim">Panglima Polim</option>
                        <option value="Alam-Sutera">Alam Sutera</option>
                        <option value="Bekasi-Summarecon">Bekasi Summarecon</option>
                        <option value="BSD">BSD</option>
                        <option value="Kelapa-Gading">Kelapa Gading</option>
                        <option value="Bandung-Naripan">Bandung Naripan</option>
                        <option value="Cibubur">Cibubur</option>
                        <option value="Bogor-Achmad Sobana">Bogor Achmad Sobana</option>
                        <option value="Kebon-Jeruk">Kebon Jeruk</option>
                        <option value="Bintaro">Bintaro</option>
                        <option value="Gading-Serpong">Gading Serpong</option>
                        <option value="Depok">Depok</option>
                        <option value="Bandung-Setiabudi">Bandung Setiabudi</option>
                        <option value="Lebak-Bulus">Lebak Bulus</option>
                        <option value="Taman-Palem">Taman Palem</option>
                        <option value="Bekasi-Galaxy-City">Bekasi Galaxy City</option>
                        <option value="Tebet">Tebet</option>
                        <option value="Sunter">Sunter</option>
                        <option value="Pantai-Indah-Kapuk">Pantai Indah Kapuk</option>
                        <option value="Citra-Raya">Citra Raya</option>
                        <option value="Bandung-Antapani">Bandung Antapani</option>
                    </select>
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
                        <input type="number" id="price" name="price" class="form-control" re aria-label="Amount (to the nearest rupiah)" required>
                        <div class="input-group-append">
                            <span class="input-group-text">.00</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-5">
                        <label for="stock">Stock <span class="text-danger">*</span></label>
                        <input type="number" id="stock" name="stock" class="normal" min="0" step="10" required />
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="discount">Discount </label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="discount" name="discount" aria-label="discount" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="promosi">Promosi </label>
                        <div class="toggle toggle-lg toggle-light"></div>
                        <input type="hidden" name="promosi" id="promosi" value="0">
                    </div>
                </div>
                <button onclick="selectThumb()" class="btn btn-primary btn-block mb-15">Select Thumbnail</button>
                <div class="form-group">
                    <img hidden class="img-fluid img-thumbnail" id="previewThumb" alt="img">
                </div>
            </section>
        </div>
    </div>

    <section class="hk-sec-wrapper">
        <h6 class="hk-sec-title">Specification <span class="text-danger">*</span></h6>
        <div  class="row">
            <div class="col-sm">
                <div class="tinymce-wrap">
                    <textarea class="tinymce" name="specification"></textarea>
                </div>
            </div>
        </div>
    </section>

    <input type="text" hidden id="image_id" name="image_ids">
    <input type="text" hidden id="thumb_id" name="thumb_id">
</form>



<!-- Thumbnail Modal -->
<div class="modal fade" id="modalThumb" tabindex="-1" role="dialog" aria-labelledby="modalThumb" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Thumbnail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm">
                        <div class="row" id="imageThumb">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

