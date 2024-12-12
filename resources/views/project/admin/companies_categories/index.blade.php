@extends('layouts.app')
@section('title')
{{__('translate.Companies Categories')}}{{--تصنيف الشركات--}}
@endsection
@section('header_title')
{{__('translate.Companies Categories')}}{{--تصنيف الشركات--}}
@endsection
@section('header_title_link')
<a href="{{ route('admin.companies.index') }}">{{__('translate.Display Companies')}}{{--استعراض الشركات--}}</a>
@endsection
@section('header_link')
<a href="{{ route('admin.companies_categories.index') }}">{{__('translate.Companies Categories')}}{{--تصنيف الشركات--}}</a>
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
<style>
    .input-error {
        border-color: #d22d3d;
    }
</style>
@endsection
@section('content')
    <div>
        <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddCompaniesCategoriesModal').modal('show')" type="button"><span
                class="fa fa-plus"></span>{{__('translate.Add Company Category')}}{{--  إضافة تصنيف شركات--}}</button>
    </div>

    <div class="card" style="padding-left:0px; padding-right:0px;">
        <div class="card-body">

            <div class="form-outline" id="showSearch" hidden>
                <input type="search" onkeyup="companies_categories_search(this.value)" class="form-control mb-2" placeholder="{{__('translate.Search')}}"
                    aria-label="Search" /> {{-- بحث --}}
            </div>
            @if(!$data->isEmpty())
            <div class="form-outline">
                <input type="search" onkeyup="companies_categories_search(this.value)" class="form-control mb-2" placeholder="{{__('translate.Search')}}"
                    aria-label="Search" /> {{-- بحث --}}
            </div>
            @endif

            <div id="showTable">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col" style="display:none;">id</th>
                                <th scope="col">{{__('translate.Company Category')}}{{-- تصنيف الشركة --}}</th>
                                <th scope="col">{{__('translate.Operations')}} {{--  العمليات --}}</th>
                            </tr>
                        </thead>
                        <tbody>
                                @if ($data->isEmpty())
                                <tr>
                                    <td colspan="2" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
                                </tr>
                                @else
                                    @foreach ($data as $key)
                                    <tr>
                                        <td>{{ $key->cc_name }}</td>
                                        <td>
                                            <button onclick="editCompaniesCategories({{ $key }})" class="btn btn-info" ><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('project.admin.companies_categories.modal.AddCompaniesCategoriesModal')
        @include('project.admin.companies_categories.modal.EditCompaniesCategoriesModal')
        @include('layouts.loader')
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>

    <script>
        document.getElementById("CompaniesCategories").addEventListener("submit", (e) => {

            e.preventDefault();

            if(document.getElementById("cc_name").value == ""){
                $('#cc_name').addClass('input-error');
            }else{
                categories = {!! json_encode($data, JSON_HEX_APOS) !!};
                categoriesLength = categories.length;


                data =$('#CompaniesCategories').serialize();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Send an AJAX request with the CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                $('#AddCompaniesCategoriesModal').modal('hide');
                $('#LoadingModal').modal('show');
                // Send an AJAX request
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.companies_categories.create') }}",
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if(categoriesLength==0){
                            document.getElementById('showSearch').hidden = false;
                        }
                        $('#LoadingModal').modal('hide');
                        $('#showTable').html(response.view);
                    },
                    error: function(xhr, status, error) {
                        console.error("error"+error);
                    }
                });
            }


    });

    $('#cc_name').on('focus', function() {
    	$('#cc_name').removeClass('input-error');
    });
    $('#edit_cc_name').on('focus', function() {
    	$('#edit_cc_name').removeClass('input-error');
    });

    $("#AddCompaniesCategoriesModal").on("hidden.bs.modal", function () {
            document.getElementById('cc_name').value = "";

            $('#cc_name').removeClass('input-error');

    });

    $("#EditCompaniesCategoriesModal").on("hidden.bs.modal", function () {
            $('#edit_cc_name').removeClass('input-error');
    });

    function editCompaniesCategories(data){
        $('#EditCompaniesCategoriesModal').modal('show');
        document.getElementById('edit_cc_name').value = data.cc_name;
        document.getElementById('edit_cc_id').value = data.cc_id;
    }

    document.getElementById("EditCompaniesCategories").addEventListener("submit", (e) => {
           e.preventDefault();

           if(document.getElementById("edit_cc_name").value == ""){
                $('#edit_cc_name').addClass('input-error');
            }else{

                data =$('#EditCompaniesCategories').serialize();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Send an AJAX request with the CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                $('#EditCompaniesCategoriesModal').modal('hide');
                $('#LoadingModal').modal('show');
                // Send an AJAX request
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.companies_categories.update') }}",
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        $('#LoadingModal').modal('hide');
                        $('#showTable').html(response.view);
                    },
                    error: function(xhr, status, error) {
                        console.error("error"+error);
                    }
                });
            }
    });

    function companies_categories_search(data) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>');
            $.ajax({
                url: "{{ route('admin.companies_categories.companies_categories_search') }}", // Replace with your own URL
                method: "post",
                data: {
                    'search': data,
                    _token: '{!! csrf_token() !!}',
                },
                success: function(data) {
                    dataTable = data;
                    $('#showTable').html(data.view);
                },
                error: function(xhr, status, error) {
                    alert('error');
                }
            });
        }


    </script>
@endsection
