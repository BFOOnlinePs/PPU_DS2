@extends('layouts.app')
@section('title')
    {{__('translate.Main')}}{{-- الرئيسية --}}
@endsection
@section('header_title')
    {{__('translate.Students')}}{{-- الطلاب --}}
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a href="{{route('communications_manager_with_companies.students.index')}}">{{__('translate.Students')}}{{-- الطلاب --}}</a>
@endsection
        @section('style')
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
        @endsection
        @section('content')
            <div class="container-fluid">
                <div class="edit-profile">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">{{ __('translate.Company Name') }}</label>
                                                        <input onkeyup="company_table_ajax()" id="company_search" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">{{ __('translate.company_status') }}</label>
                                                        <select onchange="company_table_ajax()" class="form-control" name="" id="company_status">
                                                            <option value="">{{ __('translate.Everyone') }}</option>
                                                            <option value="1">نعم</option>
                                                            <option value="0">لا</option>
                                                        </select>
                                                    </div>
                                                </div><div class="col">
                                                    <div class="form-group">
                                                        <label for="">{{ __('translate.capacity') }}</label>
                                                        <select onchange="company_table_ajax()" class="form-control" name="" id="capacity">
                                                            <option value="">{{ __('translate.Everyone') }}</option>
                                                            <option value="1">نعم</option>
                                                            <option value="0">لا</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <div id="company_table_ajax">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('project.communications_manager_with_companies.follow_up_record.modals.company_modal')
                @include('project.communications_manager_with_companies.follow_up_record.modals.add_company_contact_modal')
                @include('project.communications_manager_with_companies.follow_up_record.modals.add_branches')
                @include('project.communications_manager_with_companies.follow_up_record.modals.addAttachmentModal')
                @include('project.communications_manager_with_companies.follow_up_record.modals.studentNominationModal')
            </div>
        @endsection
        @section('script')
            <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
            <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
            <script>
                $(document).ready(function () {
                    company_table_ajax();
                    search_student_ajax();
                });
                function user_search(word_to_search)
                {
                    let m_id = document.getElementById('select-major').value;
                    $.ajax({
                        url: "{{route('communications_manager_with_companies.students.search')}}",
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'word_to_search' : word_to_search,
                            'm_id' : m_id
                        },
                        success: function(response) {
                            $('#content').html(response.html);
                        },
                        error: function() {
                            alert('Error fetching user data.');
                        }
                    });
                }

                function company_table_ajax(){

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send an AJAX request with the CSRF token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                    $.ajax({
                        url: "{{ route('communications_manager_with_companies.follow_up_record.company_table_ajax') }}",
                        method: "post",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            'company_search' : $('#company_search').val(),
                            'company_status' : $('#company_status').val(),
                            'capacity' : $('#capacity').val(),
                        },
                        success: function(data) {
                            if(data.success == 'true'){
                                $('#company_table_ajax').html(data.view);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });

                }

                function update_capacity_ajax(id,value){

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

                function company_modal(data) {
                    @if(app()->isLocale('en'))
                        $('#company_name').text(data.c_english_name)
                    @elseif(app()->isLocale('ar'))
                        $('#company_name').text(data.c_name)
                    @endif
                    $('#company_id').val(data.c_id)
                    $('#company_arabic_name').val(data.c_name)
                    $('#company_english_name').val(data.c_english_name)
                    $('#company_phone').val(data.company_branches.b_phone1)
                    $('#company_address').val(data.company_branches.b_address)
                    $('#company_type').val(data.c_type)
                    $('#company_category_id').val(data.c_category_id)
                    $('#company_website').val(data.c_website)
                    $('#company_description').val(data.c_description)
                    $('#company_modal').modal('show');
                }

                function update_company_information(key,value) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send an AJAX request with the CSRF token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                    $.ajax({
                        url: "{{ route('communications_manager_with_companies.follow_up_record.update_company_information') }}",
                        method: "post",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            'id' : $('#company_id').val(),
                            'key' : key,
                            'value' : value,
                        },
                        success: function(data) {
                            if(data.success == 'true'){
                                company_table_ajax();
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                }

                function list_contact_company() {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send an AJAX request with the CSRF token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                    $.ajax({
                        url: "{{ route('communications_manager_with_companies.follow_up_record.list_contact_company') }}",
                        method: "post",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            'id' : $('#company_id').val(),
                        },
                        success: function(data) {
                            if(data.success == 'true'){
                                $('#list_contact_company').html(data.view);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                }

                function add_company_contact_modal() {
                    $('#add_company_contact_modal').modal('show');
                }

                function create_contact_company() {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send an AJAX request with the CSRF token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                    $.ajax({
                        url: "{{ route('communications_manager_with_companies.follow_up_record.create_contact_company') }}",
                        method: "post",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            'c_id' : $('#company_id').val(),
                            'u_username' : $('#u_username').val(),
                            'name' : $('#name').val(),
                            'email' : $('#email').val(),
                            'password' : $('#password').val(),
                            'u_phone1' : $('#u_phone1').val(),
                            'u_phone2' : $('#u_phone2').val(),
                        },
                        success: function(data) {
                            if(data.success == 'true'){
                                list_contact_company();
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                }

                function delete_contact_company(id) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send an AJAX request with the CSRF token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                    $.ajax({
                        url: "{{ route('communications_manager_with_companies.follow_up_record.delete_contact_company') }}",
                        method: "post",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            'id' : id,
                        },
                        success: function(data) {
                            if(data.success == 'true'){
                                list_contact_company();
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                }

                function check_email_found(email) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send an AJAX request with the CSRF token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                    $.ajax({
                        url: "{{ route('communications_manager_with_companies.follow_up_record.check_email_found') }}",
                        method: "post",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            'email' : email,
                        },
                        success: function(data) {
                            if(data.success == 'true'){
                                if(data.status === 'found') {
                                    $('#validation_email').text(data.message).removeClass('text-success').addClass('text-danger');
                                    $('#contact_company_save_button').prop('disabled', true);
                                } else {
                                    $('#validation_email').text(data.message).removeClass('text-danger').addClass('text-success');
                                    $('#contact_company_save_button').prop('disabled', false);
                                }

                            }
                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                }

                function list_branches() {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send an AJAX request with the CSRF token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                    $.ajax({
                        url: "{{ route('communications_manager_with_companies.follow_up_record.list_branches') }}",
                        method: "post",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            'c_id' : $('#company_id').val()
                        },
                        success: function(data) {
                            if(data.success == 'true'){
                                $('#list_branches').html(data.view);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                }

                function add_branches_modal() {
                    $('#add_branches_modal').modal('show');
                }

                function list_student_company_ajax() {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send an AJAX request with the CSRF token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                    $.ajax({
                        url: "{{ route('communications_manager_with_companies.follow_up_record.list_student_company_ajax') }}",
                        method: "post",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            'sc_company_id' : $('#company_id').val()
                        },
                        success: function(data) {
                            if(data.success == 'true'){
                                $('#list_student_company').html(data.view);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                }

                $(document).ready(function () {
                    $('#branch_create_form').submit(function (e) {
                        e.preventDefault();
                        var csrfToken = $('meta[name="csrf-token"]').attr('content');

                        // Send an AJAX request with the CSRF token
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            }
                        })
                        // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                        $.ajax({
                            url: $(this).attr('action'),
                            method: "post",
                            data: {
                                _token: '{!! csrf_token() !!}',
                                'b_company_id' : $('#company_id').val(),
                                'branch_address' : $('#branch_address').val(),
                                'branch_phone1' : $('#branch_phone1').val(),
                                'branch_phone2' : $('#branch_phone2').val(),
                                'branch_manager' : $('#branch_manager').val(),
                                'branch_city' : $('#branch_city').val(),
                            },
                            success: function(data) {
                                if(data.success == 'true'){
                                    list_branches();
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('error');
                            }
                        });
                    })
                });

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
                            company_table_ajax();
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
                                company_table_ajax();
                            }
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

                function payment_table_ajax(){
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    // Send an AJAX request with the CSRF token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    // $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
                    $.ajax({
                        url: "{{ route('communications_manager_with_companies.follow_up_record.payment_table_ajax') }}",
                        method: "post",
                        data: {
                            'company_id' : $('#company_id').val(),
                            _token: '{!! csrf_token() !!}',
                        },
                        success: function(data) {
                            if(data.success === 'true'){
                                $('#payment_table').html(data.view);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });

                }
            </script>

            <script>

                $(document).ready(function () {
                    addClassToAnas();
                });

                function addClassToAnas() {
                    // Get all elements with the class name "anas"
                    // var element = document.querySelector('.page-body-wrapper');

                    // element.classList.remove('page-body-wrapper');

                    $('.main-nav').toggleClass('close_icon');
                    $('.page-main-header').toggleClass('close_icon');
                }

            </script>
@endsection
