@extends('layouts.app')

@push('assetCSS')
<!-- Data Table CSS -->
<link href="{{ env('APP_ASSET')('vendors/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ env('APP_ASSET')('vendors/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('assetJS')
<!-- Data Table JavaScript -->
<script src="{{ env('APP_ASSET')('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/jszip/dist/jszip.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ env('APP_ASSET')('vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
{{-- <script src="{{ env('APP_ASSET')('js/dataTables-data.js') }}"></script> --}}
@endpush

@push('script')
<script>
    $(function(){
        $('#productTable').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Search",
                sLengthMenu: "_MENU_items"
            }
        });
    });
</script>
@endpush

@section('content')
<section class="hk-sec-wrapper">
    <div class="button-list mb-20">
        <a href="{{ route('product.new') }}">
            <button type="button" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> New Product</button>
        </a>
    </div>

    <div class="row">
        <div class="col-sm">
            <div class="table-wrap">
                <table id="productTable" class="table table-hover w-100 display pb-30">
                    <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($products)
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->product_code }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <a href="{{ route('product.detail', $product->id) }}">
                                            <button class="btn btn-icon btn-icon-circle btn-secondary btn-icon-style-3" data-toggle="tooltip" data-placement="top" title data-original-title="View Detail">
                                                <span class="btn-icon-wrap">
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
