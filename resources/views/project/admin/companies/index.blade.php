@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{--الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.Companies Management')}}{{--إدارة الشركات--}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('admin.companies.index') }}">{{__('translate.Display Companies')}}{{--استعراض الشركات--}}</a>
@endsection
@section('style')
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            padding: 12px 16px;
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
@endsection
@section('content')

<div>
    <button class="btn btn-primary  mb-2 btn-s" type="button" onclick='location.href="{{route("admin.companies.company")}}"'><span class="fa fa-plus"></span> {{__('translate.Add Company')}}{{-- إضافة شركة --}}</button>
    <button class="btn btn-primary  mb-2 btn-s" type="button" onclick='location.href="{{route("admin.companies_categories.index")}}"'><span class="fa fa-briefcase"></span> {{__('translate.Companies Categories')}}{{-- تصنيف الشركات --}}</button>
</div>

<div class="card" style="padding-left:0px; padding-right:0px;">
    <input type="hidden" id="company_id">
    <div class="card-body" >
        @if(!$data->isEmpty())
        <div class="form-outline">
            <input type="search" onkeyup="companySearch(this.value)" class="form-control mb-2" placeholder="{{__('translate.Search')}}"
                aria-label="Search" /> {{-- بحث --}}
        </div>
        @endif
        <div id="showTable">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col" style="display:none;">id</th>
                            <th scope="col">{{__('translate.Company Name')}} {{-- اسم الشركة --}}</th>
                            <th scope="col">{{__('translate.Company Manager')}}{{-- مدير الشركة --}}</th>
                            <th scope="col">{{__('translate.Company Category')}}{{-- تصنيف الشركة --}}</th>
{{--                            <th scope="col">{{__('translate.Company Type')}}--}}{{-- نوع الشركة --}}{{--</th>--}}
                            <th scope="col">{{__('translate.capacity')}}</th>
                            <th scope="col" style="width: 200px">{{__('translate.company_status')}}</th>
                            <th scope="col" style="width: 200px">{{__('translate.Operations')}} {{--  العمليات --}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($data->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
                        </tr>
                    @else
                        @foreach ($data as $key)
                            <tr>
                                <td style="display:none;">{{ $key->c_id }}</td>
                                <td><a href="{{route('admin.users.details',['id'=> $key->manager->u_id  ?? 1 ])}}">
                                        @if(app()->isLocale('en') || (app()->isLocale('ar') && empty($key->c_name)))
                                            {{ $key->c_english_name }}
                                        @elseif(app()->isLocale('ar') || (app()->isLocale('en') && empty($key->c_english_name)))
                                            {{ $key->c_name }}
                                        @endif
                                    </a></td>
                                @if (auth()->user()->u_role_id == 1)
                                    <td><a href="{{route('admin.users.details',['id'=>$key->manager->u_id ?? 1])}}">{{$key->manager->name ?? ''}}</a></td>
                                @else
                                    <td>{{$key->manager->name ?? ''}}</td>
                                @endif

                                {{-- <td><a href="{{route('admin.companies_categories.index')}}">{{$key->companyCategories->cc_name}}</a></td> --}}
                                @if($key->companyCategories != null)
                                    <td><a href="{{route("admin.companies_categories.index")}}">{{$key->companyCategories->cc_name ?? ''}}</a></td>
                                @else
                                    <td>{{__('translate.Unspecified')}}{{--غير محدد--}}</td>
                                @endif

{{--                                @if( $key->c_type == 1)--}}
{{--                                    <td>{{__('translate.Public Sector')}}--}}{{-- قطاع عام --}}{{--</td>--}}
{{--                                @endif--}}
{{--                                @if( $key->c_type == 2) --}}
{{--                                    <td>{{__('translate.Private Sector')}}--}}{{-- قطاع خاص --}}{{--</td>--}}
{{--                                @endif--}}
                                <td>
                                    <input type="text" onchange="update_capacity_ajax({{ $key->c_id }},this.value)" class="form-control" value="{{ $key->c_capacity }}" placeholder="">
                                </td>
                                <td>
                                    <label class="switch">
                                        <input onchange="update_company_status({{ $key->c_id }},this.checked)" type="checkbox" @if($key->c_status == 1) checked="" @endif><span class="switch-state"></span>
                                    </label>
                                </td>
                                <td class="">
                                    <div class="dropdown">
                                        <span data-feather="more-vertical" ></span>
                                        <div class="dropdown-content">
                                            <button class="btn btn-dark btn-sm form-control m-1"><a style="cursor: pointer;font-size: 10px" class="text-white" onclick='location.href="{{route("admin.companies.edit",["id"=>$key->c_id])}}"'>تفاصيل الشركة</a></button>
                                            <button class="btn btn-dark btn-sm form-control m-1"><a style="cursor: pointer;font-size: 10px" class="text-white" onclick='show_student_nomination_modal({{ $key }})'>اقتراحات الطلاب</a></button>
                                            <button class="btn btn-dark btn-sm form-control m-1"><a style="cursor: pointer;font-size: 10px" class="text-white" onclick='addAttachmentModal({{ $key->c_id }})'>اضافة اتفاقية</a></button>
                                        </div>
                                    </div>
{{--                                    <button class="btn btn-dark btn-xs" onclick='location.href="{{route("admin.companies.edit",["id"=>$key->c_id])}}"'><i class="fa fa-search"></i></button>--}}
{{--                                    <button class="btn btn-dark btn-xs" data-container="body" onclick='show_student_nomination_modal({{ $key }})'><i class="fa fa-group"></i></button>--}}
{{--                                    <button class="btn btn-dark btn-xs" data-container="body" onclick='addAttachmentModal({{ $key->c_id }})'><i class="fa fa-file"></i></button>--}}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                </table>
            </div>
        </div>

    </div>
    @include('project.admin.companies.modals.studentNominationModal')
    @include('project.admin.companies.modals.addAttachmentModal')
    @include('project.admin.companies.modals.uncompletedCompanyModal')
</div>

@endsection


@section('script')

<script>

let uncompletedCompanySize = 0;
let uncompletedCompany;

    $(document).ready(function () {
        search_student_ajax();
        $('.dropdown-toggle').dropdown();

        uncompletedCompanySize = {{count($uncompletedCompany)}};
    if(uncompletedCompanySize != 0){
        uncompletedCompany = {!! json_encode($uncompletedCompany, JSON_HEX_APOS) !!};

        x=""
        for(i=0;i<uncompletedCompanySize;i++){

            x += `<div class="row mb-2">
                    <div class="col-md-6">
                        <h6>
                            ${uncompletedCompany[i].c_name}
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <a type="button" class="btn btn-secondary" onclick="completeCompany(${i})">{{__('translate.Complete')}}</a>
                    </div>
                  </div>`
        }

        $('#p_company').html(x);
        //show popup with companies and links to them
        $('#uncompletedCompanyModal').modal('show');
    }
    });

function companySearch(data){

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send an AJAX request with the CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
    $.ajax({
        url: "{{ route('admin.companies.companySearch') }}",
        method: "post",
        data: {
            'search': data,
            _token: '{!! csrf_token() !!}',
        },
        success: function(data) {
            $('#showTable').html(data.view);
        },
        error: function(xhr, status, error) {
            alert('error');
        }
    });

}

function search_student_ajax(){
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    // Send an AJAX request with the CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
    $.ajax({
        url: "{{ route('admin.companies.search_student_ajax') }}",
        method: "post",
        data: {
            'search_student' : $('.search_student').val(),
            'major_id' : $('#major_id').val(),
            _token: '{!! csrf_token() !!}',
        },
        success: function(data) {
            $('#search_student_table').html(data.view);
        },
        error: function(xhr, status, error) {
            alert('error');
        }
    });

}

function student_nomination_table_ajax(company_id){
    $('#company_id').val(company_id);
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send an AJAX request with the CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
    $.ajax({
        url: "{{ route('admin.companies.student_nomination_table_ajax') }}",
        method: "post",
        data: {
            'company_id': company_id,
            _token: '{!! csrf_token() !!}',
        },
        success: function(data) {
            $('#student_nomination_table').html(data.view);
        },
        error: function(xhr, status, error) {
            alert('error');
        }
    });

}

function add_nomination_table_ajax(data){

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send an AJAX request with the CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
    $.ajax({
        url: "{{ route('admin.companies.add_nomination_table_ajax') }}",
        method: "post",
        data: {
            'student_id' : data.u_id,
            'company_id': $('#company_id').val(),
            _token: '{!! csrf_token() !!}',
        },
        success: function(data) {
            student_nomination_table_ajax($('#company_id').val());
            search_student_ajax();
        },
        error: function(xhr, status, error) {
            alert('error');
        }
    });

}

function delete_nomination_table_ajax(id){

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send an AJAX request with the CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
    $.ajax({
        url: "{{ route('admin.companies.delete_nomination_table_ajax') }}",
        method: "post",
        data: {
            'id' : id,
            _token: '{!! csrf_token() !!}',
        },
        success: function(data) {
            console.log(data);
            if(data.success == 'true'){
                student_nomination_table_ajax($('#company_id').val());
                search_student_ajax();
            }
        },
        error: function(xhr, status, error) {
            alert('error');
        }
    });

}

function update_capacity_ajax(id,value){
    if(value < 0){
        alert('لا يمكن ادخال اقل من 0');
        return false;
    }
    if(isNaN(value)){
        alert('لا يمكن ادخال حروف');
        return false;
    }
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send an AJAX request with the CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
    $.ajax({
        url: "{{ route('admin.companies.update_capacity_ajax') }}",
        method: "post",
        data: {
            'id' : id,
            _token: '{!! csrf_token() !!}',
            'value' : value
        },
        success: function(data) {
            if(data.success == 'true'){
                student_nomination_table_ajax($('#company_id').val());
                // search_student_ajax();
            }
        },
        error: function(xhr, status, error) {
            alert('error');
        }
    });

}

function update_company_status(id,status){
    var checked = 'true';
    if(status === false){
        checked = 0;
    }
    else{
        checked = 1;
    }
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send an AJAX request with the CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
    $.ajax({
        url: "{{ route('admin.companies.update_company_status') }}",
        method: "post",
        data: {
            'company_id' : id,
            _token: '{!! csrf_token() !!}',
            'status' : checked
        },
        success: function(data) {
            if(data.success == 'true'){

            }
        },
        error: function(xhr, status, error) {
            alert('error');
        }
    });

}

function file_attachment_list_ajax(){

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send an AJAX request with the CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    $('#file_attachment_list').html('<div class="text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
    $.ajax({
        url: "{{ route('file_attachment.file_attachment_list_ajax') }}",
        method: "post",
        data: {
            'table_name' : 'companies',
            'table_name_id' : $('#table_name_id').val(),
            'view' : 'project.admin.companies.ajax.file_attachment_list',
            _token: '{!! csrf_token() !!}',
        },
        success: function(response) {
            if(response.success == 'true'){
                $('#file_attachment_list').html(response.view);
            }
        },
        error: function(xhr, status, error) {
            alert('error');
        }
    });

}

function addAttachmentModal(table_name_id) {
    $('#addAttachmentModal').modal('show');
    $('#table_name_id').val(table_name_id);
    file_attachment_list_ajax();
}

function show_student_nomination_modal(data) {
    student_nomination_table_ajax(data.c_id);
    $('#AddStudentNominationModal').modal('show');
}


function completeCompany(index) {
    const companyId = uncompletedCompany[index].id; // Assume each company has an `id`
    const sessionValue = "{{ session('your_session_key') }}"; // Get the session value

    $.ajax({
        url: '{{ route("admin.companies.company") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            company_id: companyId,
            session_value: sessionValue // Send session value
        },
        success: function(response) {
            alert('asd'); // Show success message
            // Optionally remove the company from the list or refresh the modal
        },
        error: function(xhr) {
            alert('An error occurred: ' + xhr.responseText);
        }
    });
}

</script>

@endsection
