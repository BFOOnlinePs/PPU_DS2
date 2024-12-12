

@extends('layouts.app')
@section('title')
{{__("translate.Training Places")}}{{-- أماكن التدريب --}}
@endsection
@section('header_title')
{{__("translate.Training Places")}}{{-- أماكن التدريب --}}
@endsection
@section('header_title_link')
<a href="{{route('admin.users.index')}}">{{__('translate.Users')}}{{-- المستخدمين --}}</a>
@endsection
@section('header_link')
<a href="{{route('admin.users.details' , ['id'=>$user->u_id])}}">{{$user->name}}</a> / {{__("translate.Training Places")}}{{-- أماكن التدريب --}}
@endsection



@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="page-header pb-1">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="p-2 pt-0 row">
        @include('project.admin.users.includes.menu_student')
    </div>
    <div class="edit-profile">
        <div class="row">
            <div class="col-xl-3">
                @include('project.admin.users.includes.information_edit_card_student')
        </div>
        <div class="col-xl-9">
          <form class="card">
            <div class="card-header pb-0">
              {{-- <h4 class="card-title mb-0">{{__('translate.Training Places')}} أماكن التدريب</h4> --}}
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
              <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm custom-btn" onclick="$('#AddPlacesTrainingModal').modal('show')" type="button"><span class="fa fa-plus"></span> {{__('translate.Enroll student in training')}} {{-- تسجيل الطالب في تدريب --}}</button>
                        </div>
                    </div>
              </div>
              <div class="row" id="content">
                @include('project.admin.users.ajax.placesTrainingList')
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    @include('project.admin.users.modals.add_places_training')
    @include('project.admin.users.modals.edit_places_training')
    @include('project.admin.users.modals.loading')
    @include('project.admin.users.modals.agreement_file')
    @include('project.admin.users.modals.alertToConfirmDelete')
  </div>
@endsection
@section('script')
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        function openAlertDelete(sc_id)
        {
            document.getElementById('student_company_id').value = sc_id;
            $('#confirmDeleteTraining').modal('show');
        }
        function confirmDeleteTraining()
        {
            let sc_id = document.getElementById('student_company_id').value;
            $.ajax({
                beforeSend: function(){
                    $('#LoadingModal').modal('show');
                },
                url: "{{route('admin.users.places.training.delete')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'sc_id': sc_id
                },
                success: function(response) {
                    $('#LoadingModal').modal('hide');
                    $('#confirmDeleteTraining').modal('hide');
                    $('#content').html(response.html);
                },
                error: function() {
                    $('#LoadingModal').modal('hide');
                }
            });
        }
        function active()
        {
            let status = document.getElementById('select_status');
            let option = document.createElement('option');
            option.value = 1;
            option.text = `نشط`;
            status.appendChild(option);
        }
        function deactive()
        {
            let status = document.getElementById('select_status');
            let option = document.createElement('option');
            option.value = 2;
            option.text = `{{__('translate.finished')}}`;
            status.appendChild(option);
        }
        function branch_change_editing(branch_id)
        {
            $.ajax({
                url: "{{route('admin.users.places.training.edit.branch')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'branch_id' : branch_id
                },
                success: function(response) {
                    {
                        let departmentSelect = document.getElementById('selectEditDepartment');
                        departmentSelect.removeAttribute('disabled'); // Enable the select
                        // Loop through all options and remove them
                        while (departmentSelect.options.length > 0) {
                            departmentSelect.remove(0);
                        }
                        {
                            let option = document.createElement('option');
                            option.value = null;
                            option.text = "";
                            departmentSelect.appendChild(option);
                        }
                        response.departments.forEach(function(department) {
                            let option = document.createElement('option');
                            option.value = department.d_id;
                            option.text = department.d_name;
                            departmentSelect.appendChild(option);
                        });
                    }
                },
                error: function() {
                }
            });
        }
        function open_edit_modal(studentCompany , branch_name , status , trainer , department_name , course_name)
        {
            {
                // To set company name
                let company = document.getElementById('c_name');
                company.innerHTML = "";
                let option = document.createElement('option');
                option.value = studentCompany.sc_company_id;
                option.text = studentCompany.company.c_name;
                company.appendChild(option);
            }
            {
                // To set training status
                let statusSelect = document.getElementById('select_status');
                // Loop through all options and remove them
                while (statusSelect.options.length > 0) {
                    statusSelect.remove(0);
                }
                if(status === 1) {
                    active();
                    deactive();
                }
                else if (status === 2) {
                    deactive();
                    active();
                }
            }
            document.getElementById('sc_id').value = studentCompany.sc_id;
            $.ajax({
                url: "{{route('admin.users.places.training.edit')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'sc_id' : studentCompany.sc_id
                },
                success: function(response) {
                    {
                        let courseSelect = document.getElementById('selectEditCourse');
                        // Loop through all options and remove them
                        while (courseSelect.options.length > 0) {
                            courseSelect.remove(0);
                        }
                        {
                            let option = document.createElement('option');
                            option.value = studentCompany.sc_registration_id;
                            option.text = course_name;
                            courseSelect.appendChild(option);
                        }
                        response.courses.forEach(function(course) {
                            let option = document.createElement('option');
                            option.value = course.r_id;
                            option.text = course.courses.c_name;
                            courseSelect.appendChild(option);
                        });
                    }
                    {
                        let trainerSelect = document.getElementById('selectEditTrainer');
                        // Loop through all options and remove them
                        while (trainerSelect.options.length > 0) {
                            trainerSelect.remove(0);
                        }
                        {
                            let option = document.createElement('option');
                            if(trainer != null) {
                                option.value = studentCompany.sc_mentor_trainer_id;
                                option.text = trainer;
                            }
                            trainerSelect.appendChild(option);
                            {
                                let option = document.createElement('option');
                                option.value = null;
                                option.text = "";
                                trainerSelect.appendChild(option);
                            }
                        }
                        response.trainers.forEach(function(trainer) {
                            let option = document.createElement('option');
                            option.value = trainer.u_id;
                            option.text = trainer.name;
                            trainerSelect.appendChild(option);
                        });
                    }

                    // To set branch name
                    {
                        let branchSelect = document.getElementById('selectEditBranch');
                        // Loop through all options and remove them
                        while (branchSelect.options.length > 0) {
                            branchSelect.remove(0);
                        }
                        {
                            let branch = document.getElementById('selectEditBranch');
                            let option = document.createElement('option');
                            option.value = studentCompany.sc_branch_id;
                            option.text = branch_name;
                            branch.appendChild(option);
                        }
                        response.branches.forEach(function(branch) {
                            let option = document.createElement('option');
                            option.value = branch.b_id;
                            option.text = branch.b_address;
                            branchSelect.appendChild(option);
                        });
                    }

                    // To set department name
                    {
                        let departmentSelect = document.getElementById('selectEditDepartment');
                        departmentSelect.removeAttribute('disabled');
                        // Loop through all options and remove them
                        while (departmentSelect.options.length > 0) {
                            departmentSelect.remove(0);
                        }
                        if(department_name != null){
                            let department = document.getElementById('selectEditDepartment');
                            let option = document.createElement('option');
                            option.value = studentCompany.sc_department_id;
                            option.text = department_name;
                            department.appendChild(option);
                        }
                        let option = document.createElement('option');
                        option.value = null;
                        option.text = "";
                        departmentSelect.appendChild(option);
                        response.departments.forEach(function(department) {
                            let option = document.createElement('option');
                            option.value = department.d_id;
                            option.text = department.d_name;
                            departmentSelect.appendChild(option);
                        });
                    }
                },
                error: function() {
                }
            });
            $('#EditPlacesTrainingModal').modal('show');
        }
        function change_branch(branch_id)
        {
            $.ajax({
                url: "{{route('admin.users.places.training.departments')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'branch_id': branch_id
                },
                success: function(response) {
                    {
                        let departmentSelect = document.getElementById('select-departments');
                        departmentSelect.removeAttribute('disabled'); // Enable the select
                        // Loop through all options and remove them
                        while (departmentSelect.options.length > 0) {
                            departmentSelect.remove(0);
                        }
                        {
                            let option = document.createElement('option');
                            option.text = "";
                            departmentSelect.appendChild(option);
                        }
                        response.departments.forEach(function(department) {
                            let option = document.createElement('option');
                            option.value = department.d_id;
                            option.text = department.d_name;
                            departmentSelect.appendChild(option);
                        });
                    }
                },
                error: function() {
                }
            });
        }
        function change_company(company_id)
        {
            $.ajax({
                url: "{{route('admin.users.places.training.branches')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'company_id': company_id
                },
                success: function(response) {
                    {
                        let branchSelect = document.getElementById('select-branches');
                        branchSelect.removeAttribute('disabled'); // Enable the select
                        // Loop through all options and remove them
                        while (branchSelect.options.length > 0) {
                            branchSelect.remove(0);
                        }
                        response.branches.forEach(function(branch) {
                            let option = document.createElement('option');
                            option.value = branch.b_id;
                            option.text = branch.b_address;
                            branchSelect.appendChild(option);
                        });
                    }
                    {
                        let trainerSelect = document.getElementById('select-trainers');
                        trainerSelect.removeAttribute('disabled'); // Enable the select
                        // Loop through all options and remove them
                        while (trainerSelect.options.length > 0) {
                            trainerSelect.remove(0);
                        }
                        {
                            let option = document.createElement('option');
                            option.text = "";
                            trainerSelect.appendChild(option);
                        }
                        response.trainers.forEach(function(trainer) {
                            let option = document.createElement('option');
                            option.value = trainer.u_id;
                            option.text = trainer.name;
                            trainerSelect.appendChild(option);
                        });
                    }
                    {
                        let departmentSelect = document.getElementById('select-departments');
                        departmentSelect.removeAttribute('disabled'); // Enable the select
                        // Loop through all options and remove them
                        while (departmentSelect.options.length > 0) {
                            departmentSelect.remove(0);
                        }
                        {
                            let option = document.createElement('option');
                            option.text = "";
                            departmentSelect.appendChild(option);
                        }
                        response.departments.forEach(function(department) {
                            let option = document.createElement('option');
                            option.value = department.d_id;
                            option.text = department.d_name;
                            departmentSelect.appendChild(option);
                        });
                    }
                },
                error: function() {
                }
            });
        }
        function clear_modal_add()
        {
            let form = ['addPlacesTrainingForm'];
            let selects = ['select-companies' , 'select-branches' , 'select-trainers' , 'select-departments'];
            for(let i = 0; i < form.length; i++)
            {
                document.getElementById(form[i]).reset();
            }
            for(let i = 0; i < selects.length; i++)
            {
                $('#' + selects[i]).select2('destroy').val('').select2();
            }
            for(let i = 1; i < selects.length; i++)
            {
                $('#' + selects[i]).prop('disabled', true);
            }
            const errorContainer = document.getElementById('error-container');
            errorContainer.innerHTML = ''; // Clear previous errors
        }
        function update_places_training()
        {
            if($('#selectEditBranch').val() === 'null'){
                alert('يرجى ملئ حقل اسم الفرع');
            }
            else{
                let sc_id = document.getElementById('sc_id').value;
                let sc_branch_id = document.getElementById('selectEditBranch').value;
                let sc_department_id = document.getElementById('selectEditDepartment').value;
                let sc_status = document.getElementById('select_status').value;
                let sc_mentor_trainer_id = document.getElementById('selectEditTrainer').value;
                let sc_registration_id = document.getElementById('selectEditCourse').value;
                $.ajax({
                    beforeSend: function(){
                        $('#EditPlacesTrainingModal').modal('hide');
                        $('#LoadingModal').modal('show');
                    },
                    url: "{{route('admin.users.places.training.update')}}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'sc_id' : sc_id,
                        'sc_branch_id' : sc_branch_id,
                        'sc_department_id' : sc_department_id,
                        'sc_status' : sc_status,
                        'sc_mentor_trainer_id' : sc_mentor_trainer_id,
                        'sc_registration_id' : sc_registration_id
                    },
                    success: function(response) {
                        $('#EditPlacesTrainingModal').modal('hide');
                        $('#content').html(response.html);
                    },
                    complete: function(){
                        $('#LoadingModal').modal('hide');
                    },
                    error: function(jqXHR) {
                    }
                });
            }
        }
        function submitFile(input, sc_id) {
            let file = input.files[0];
            if (file) {
                let formData = new FormData();
                formData.append('file_company_student', file);
                formData.append('id_company_student', sc_id);
                formData.append('sc_student_id', document.getElementById('u_id').value);

                $(`#progress-container${sc_id}`).show();

                // Make an AJAX request to submit the file
                $.ajax({
                    url: "{{ route('admin.users.training.place.update.file_agreement') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function () {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (event) {
                            if (event.lengthComputable) {
                                var percentComplete = (event.loaded / event.total) * 100;
                                $(`#progress-bar${sc_id}`).css('width', percentComplete + '%');
                                $(`#progress-bar${sc_id}`).attr('aria-valuenow', percentComplete);
                                $(`#progress-text${sc_id}`).text('Uploading: ' + percentComplete.toFixed(2) + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        // Handle success, if needed
                        $('#content').html(response.html);
                        $('#progress-container').hide();
                    },
                    error: function (error) {
                        // Handle error, if needed
                        console.error(error);
                        $('#progress-container').hide();
                    }
                });
            }
        }
        function viewAttachment(url) {
            document.getElementById('view_attachment_result').src = url;
            $('#AgreementFileModal').modal('show');
        }
    $(document).ready(function() {
        $('#addPlacesTrainingForm').submit(function(e) {
            e.preventDefault();
            // data = $('#addPlacesTrainingForm').serialize();
            var formData = new FormData(this);
            id = document.getElementById('u_id').value;
            formData.append('id', id);
            $.ajax({
                beforeSend: function(){
                    // $('#AddPlacesTrainingModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{route('admin.users.places.training.add')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    clear_modal_add();
                    $('#LoadingModal').modal('hide');
                    $('#AddPlacesTrainingModal').modal('hide');
                    $('#content').html(response.html);
                },
                error: function(xhr) {
                    $('#LoadingModal').modal('hide');
                    clear_modal_add();
                    const errorContainer = document.getElementById('error-container');
                    errorContainer.innerHTML = ''; // Clear previous errors
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;

                        Object.values(errors).forEach(errorMessage => {
                            const errorDiv = document.createElement('div');
                            errorDiv.style = 'color: red';
                            errorDiv.textContent = '• ' + errorMessage;
                            errorContainer.appendChild(errorDiv);
                        });
                    } else {
                        const errorDiv = document.createElement('div');
                        errorDiv.textContent = 'Error: ' + xhr.statusText;
                        errorContainer.appendChild(errorDiv);
                    }
                }
            });
        })});
    </script>
    @endsection
