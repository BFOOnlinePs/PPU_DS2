@extends('layouts.app')
@section('title')
    التقييمات
@endsection
@section('header_title')
    اضافة تقييم
@endsection
@section('header_title_link')
    اضافة تقييم
@endsection
@section('header_link')
    اضافة تقييم
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.evaluations.create') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">نوع التقييم</label>
                                    <select class="form-control" required name="e_type_id" id="">
                                        <option value="">اختر نوع التقييم ...</option>
                                        @foreach($evaluation_type as $key)
                                            <option value="{{ $key->et_id }}">{{ $key->et_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">عنوان التقييم</label>
                                    <input type="text" name="e_title" class="form-control" placeholder="عنوان التقييم">
                                </div>
                            </div>
{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="">المستخدم الموجه له التقييم</label>--}}
{{--                                    <select class="form-control" name="e_evaluator_role_id" id="">--}}
{{--                                        @foreach($roles as $key)--}}
{{--                                            @if($key->r_name != 'أدمن')--}}
{{--                                                <option value="{{ $key->r_id }}">{{ $key->r_name }}</option>--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">بداية الوقت</label>
                                    <input type="datetime-local" name="e_start_time" class="form-control" placeholder="عنوان التقييم">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">نهاية الوقت</label>
                                    <input type="datetime-local" name="e_end_time" class="form-control" placeholder="عنوان التقييم">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary">حفظ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>

    {{--    <script>--}}
    {{--        document.getElementById("CompaniesCategories").addEventListener("submit", (e) => {--}}

    {{--            e.preventDefault();--}}

    {{--            if(document.getElementById("cc_name").value == ""){--}}
    {{--                $('#cc_name').addClass('input-error');--}}
    {{--            }else{--}}
    {{--                categories = {!! json_encode($data, JSON_HEX_APOS) !!};--}}
    {{--                categoriesLength = categories.length;--}}


    {{--                data =$('#CompaniesCategories').serialize();--}}
    {{--                var csrfToken = $('meta[name="csrf-token"]').attr('content');--}}

    {{--                // Send an AJAX request with the CSRF token--}}
    {{--                $.ajaxSetup({--}}
    {{--                    headers: {--}}
    {{--                        'X-CSRF-TOKEN': csrfToken--}}
    {{--                    }--}}
    {{--                });--}}

    {{--                $('#AddCompaniesCategoriesModal').modal('hide');--}}
    {{--                $('#LoadingModal').modal('show');--}}
    {{--                // Send an AJAX request--}}
    {{--                $.ajax({--}}
    {{--                    type: 'POST',--}}
    {{--                    url: "{{ route('admin.companies_categories.create') }}",--}}
    {{--                    data: data,--}}
    {{--                    dataType: 'json',--}}
    {{--                    success: function(response) {--}}
    {{--                        if(categoriesLength==0){--}}
    {{--                            document.getElementById('showSearch').hidden = false;--}}
    {{--                        }--}}
    {{--                        $('#LoadingModal').modal('hide');--}}
    {{--                        $('#showTable').html(response.view);--}}
    {{--                    },--}}
    {{--                    error: function(xhr, status, error) {--}}
    {{--                        console.error("error"+error);--}}
    {{--                    }--}}
    {{--                });--}}
    {{--            }--}}


    {{--        });--}}

    {{--        $('#cc_name').on('focus', function() {--}}
    {{--            $('#cc_name').removeClass('input-error');--}}
    {{--        });--}}
    {{--        $('#edit_cc_name').on('focus', function() {--}}
    {{--            $('#edit_cc_name').removeClass('input-error');--}}
    {{--        });--}}

    {{--        $("#AddCompaniesCategoriesModal").on("hidden.bs.modal", function () {--}}
    {{--            document.getElementById('cc_name').value = "";--}}

    {{--            $('#cc_name').removeClass('input-error');--}}

    {{--        });--}}

    {{--        $("#EditCompaniesCategoriesModal").on("hidden.bs.modal", function () {--}}
    {{--            $('#edit_cc_name').removeClass('input-error');--}}
    {{--        });--}}

    {{--        function editCompaniesCategories(data){--}}
    {{--            $('#EditCompaniesCategoriesModal').modal('show');--}}
    {{--            document.getElementById('edit_cc_name').value = data.cc_name;--}}
    {{--            document.getElementById('edit_cc_id').value = data.cc_id;--}}
    {{--        }--}}

    {{--        document.getElementById("EditCompaniesCategories").addEventListener("submit", (e) => {--}}
    {{--            e.preventDefault();--}}

    {{--            if(document.getElementById("edit_cc_name").value == ""){--}}
    {{--                $('#edit_cc_name').addClass('input-error');--}}
    {{--            }else{--}}

    {{--                data =$('#EditCompaniesCategories').serialize();--}}
    {{--                var csrfToken = $('meta[name="csrf-token"]').attr('content');--}}

    {{--                // Send an AJAX request with the CSRF token--}}
    {{--                $.ajaxSetup({--}}
    {{--                    headers: {--}}
    {{--                        'X-CSRF-TOKEN': csrfToken--}}
    {{--                    }--}}
    {{--                });--}}

    {{--                $('#EditCompaniesCategoriesModal').modal('hide');--}}
    {{--                $('#LoadingModal').modal('show');--}}
    {{--                // Send an AJAX request--}}
    {{--                $.ajax({--}}
    {{--                    type: 'POST',--}}
    {{--                    url: "{{ route('admin.companies_categories.update') }}",--}}
    {{--                    data: data,--}}
    {{--                    dataType: 'json',--}}
    {{--                    success: function(response) {--}}
    {{--                        $('#LoadingModal').modal('hide');--}}
    {{--                        $('#showTable').html(response.view);--}}
    {{--                    },--}}
    {{--                    error: function(xhr, status, error) {--}}
    {{--                        console.error("error"+error);--}}
    {{--                    }--}}
    {{--                });--}}
    {{--            }--}}
    {{--        });--}}

    {{--        function companies_categories_search(data) {--}}
    {{--            var csrfToken = $('meta[name="csrf-token"]').attr('content');--}}
    {{--            $.ajaxSetup({--}}
    {{--                headers: {--}}
    {{--                    'X-CSRF-TOKEN': csrfToken--}}
    {{--                }--}}
    {{--            });--}}
    {{--            $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>');--}}
    {{--            $.ajax({--}}
    {{--                url: "{{ route('admin.companies_categories.companies_categories_search') }}", // Replace with your own URL--}}
    {{--                method: "post",--}}
    {{--                data: {--}}
    {{--                    'search': data,--}}
    {{--                    _token: '{!! csrf_token() !!}',--}}
    {{--                },--}}
    {{--                success: function(data) {--}}
    {{--                    dataTable = data;--}}
    {{--                    $('#showTable').html(data.view);--}}
    {{--                },--}}
    {{--                error: function(xhr, status, error) {--}}
    {{--                    alert('error');--}}
    {{--                }--}}
    {{--            });--}}
    {{--        }--}}


    {{--    </script>--}}
@endsection
