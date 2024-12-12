@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{-- الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.Courses Management')}}{{--إدارة التدريبات العملية--}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('admin.courses.index')}}">{{__('translate.Courses')}}{{--التدريبات العملية--}}</a>
@endsection
@section('style')
<style>
.input-error {
    border-color: #d22d3d;
}
.input-container {
      position: relative;
      /* width: 300px; Set the width of the input container */
    }

    .icon {
      position: absolute;
      /* right: 20px; */
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
    }

    .icon_spinner {
      position: absolute;
      left: 20px;
      top: 30%;
      transform: translateY(-50%);
    }

    /* Style the input to provide some spacing for the icon */
    input {
      padding-left: 30px; /* Add padding to the left of the input to make room for the icon */
      width: 100%; /* Make the input take up the full width of the container */
    }
</style>

@endsection
@section('content')

    <div>
        <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddCourseModal').modal('show')" type="button"><span class="fa fa-plus"></span>{{__('translate.Add Course')}}{{-- إضافة تدريب عملي --}}</button>
        <button class="btn btn-primary  mb-2 btn-s" onclick='location.href="{{ route("admin.semesterCourses.index")}}"' type="button"><span class="fa fa-book"></span> {{__('translate.Current Semester Courses')}}{{-- التدريبات العملية للفصل الحالي --}}</button>
    </div>


    <div class="card" style="padding-left:0px; padding-right:0px;">

        <div class="card-body" >
            <div class="form-outline" id="showSearch" hidden>
                <input type="search" onkeyup="courseSearch(this.value)" class="form-control mb-2" placeholder="{{__('translate.Search')}}"
                    aria-label="Search" /> {{-- بحث --}}
            </div>
            @if(!$data->isEmpty())
            <div class="form-outline">
                <input type="search" onkeyup="courseSearch(this.value)" class="form-control mb-2" placeholder="{{__('translate.Search')}}"
                    aria-label="Search" /> {{-- بحث --}}
            </div>
            @endif
            <div id="showTable">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="coursesTable">
                        <thead>
                            <tr>
                                <th scope="col" style="display:none;">id</th>
                                <th scope="col">{{__('translate.Course Name')}} {{-- اسم التدريب العملي --}}</th>
                                <th scope="col">{{__('translate.Course Code')}}{{-- رمز التدريب العملي --}}</th>
                                <th scope="col">{{__('translate.Course Hours')}}{{-- ساعات التدريب العملي --}}</th>
                                <th scope="col">{{__('translate.Course Type')}}{{-- نوع التدريب العملي --}}</th>
                                <th scope="col">{{__('translate.Operations')}} {{--  العمليات --}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
                                </tr>
                            @else
                                @foreach ($data as $key)
                                    <tr data-id="{{ $key->c_id }}">
                                        <td style="display:none;">{{ $key->c_id }}</td>
                                        <td>{{ $key->c_name }}</td>
                                        <td>{{ $key->c_course_code }}</td>
                                        <td>{{ $key->c_hours }}</td>
                                        @if( $key->c_course_type == 0) <td>{{__('translate.Theoretical')}} {{-- نظري --}}</td>@endif
                                        @if( $key->c_course_type == 1) <td>{{__('translate.Practical')}} {{-- عملي --}}</td>@endif
                                        @if( $key->c_course_type == 2) <td>{{__('translate.Theoretical - Practical')}} {{-- نظري - عملي --}}</td>@endif
                                        <td id="table_buttons_{{$key->c_id}}">
                                            <button class="btn btn-info" onclick="showCourseModal({{ $key }})"><i class="fa fa-info"></i></button>
                                            <button class="btn btn-primary" onclick="showEditCourseModal({{ $key }})"><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>


            @include('project.admin.courses.modals.editCourseModal')

            @include('project.admin.courses.modals.showCourseModal')

            @include('project.admin.courses.modals.addCourseModal')


            @include('project.admin.courses.modals.loadingModal')

            <div id="auto-load" hidden>
                <div class="loader-box">
                    <div class="loader-3" ></div>
                </div>
            </div>

        </div>



    </div>

@endsection


@section('script')
    <script>
        let addCourseForm = document.getElementById("addCourseForm");
        let editCourseForm = document.getElementById("editCourseForm");

        var add_button_code = false;
        var add_button_ref = false;

        var edit_button_code = false;
        var edit_button_ref = false;

        var page = 1;
        var last_page = {!! json_encode($last_page, JSON_HEX_APOS) !!};


        let stop= false;

        let language = document.documentElement.lang

        $(document).ready(function () {
            var iconSpinners = document.querySelectorAll('.icon_spinner');
            var icons = document.querySelectorAll('.icon');

            iconSpinners.forEach((iconSpinner) => {

                iconSpinner.style.left = 'auto';
                iconSpinner.style.right = 'auto';
                iconSpinner.style.position = 'absolute';
                iconSpinner.style.top = '30%';
                iconSpinner.style.transform = 'translateY(-50%)';

                if(language=='ar'){
                    iconSpinner.style.left = '20px';
                }else{
                    iconSpinner.style.right = '20px';
                }

            });

            icons.forEach((icon) => {

                icon.style.left = 'auto';
                icon.style.right = 'auto';
                icon.style.position = "absolute";
                icon.style.top = "50%";
                icon.style.transform = "translateY(-50%)";

                if(language=='ar'){
                    icon.style.left = '20px';
                }else{
                    icon.style.right = '20px';
                }

            });
        });


        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() + 1 >= $(document).height() && stop == false) {
                page++;
                infinteLoadMore(page);
            }
        });

        function infinteLoadMore(page) {

            var editLink = "{{ route('admin.courses.loadMoreCourses', ['page' => 'page_id']) }}";
            editLink = editLink.replace('page_id', page);

            if(page<=last_page){

                //to prevent it from calling an ajax while the first one doesn't complete
                stop = true;

                $.ajax({
                    url: editLink,
                    datatype: "json",
                    type: "get",
                    beforeSend: function () {
                        //to show the loading under the table
                        document.getElementById('auto-load').hidden=false
                    },
                    success: function(response) {

                        last_page = response.data.last_page;//check thisssssssssssssssssssss

                        courses = response.data.data

                        //to get the table and then add the new rows
                        var tableBody = document.getElementById('coursesTable').getElementsByTagName('tbody')[0];

                        //to add a row for each course in the page
                        courses.forEach(function (next) {

                            var newRow = tableBody.insertRow();
                            newRow.setAttribute("data-id", next.c_id);

                            var cell0 = newRow.insertCell(0);
                            cell0.innerHTML = `${next.c_id}`;
                            cell0.style.display = "none";

                            var cell1 = newRow.insertCell(1);
                            cell1.innerHTML = `${next.c_name}`;
                            var cell2 = newRow.insertCell(2);
                            cell2.innerHTML = `${next.c_course_code}`;
                            var cell3 = newRow.insertCell(3);
                            cell3.innerHTML = `${next.c_hours}`;
                            var cell4 = newRow.insertCell(4);
                            if( `${next.c_course_type}` == 0){
                                cell4.innerHTML = "{{__('translate.Theoretical')}}";
                            }else if( `${next.c_course_type}` == 1){
                                cell4.innerHTML = "{{__('translate.Practical')}}";
                            }else if( `${next.c_course_type}` == 2){
                                cell4.innerHTML = "{{__('translate.Theoretical - Practical')}}";
                            }

                            var cell5 = newRow.insertCell(5);
                            cell5.id = `table_buttons_${next.c_id}`

                            //to pass the json object from js to html
                            var jsonToHTML = JSON.stringify(next).replace(/"/g, "&quot;");

                            cell5.innerHTML = `
                                <button class="btn btn-info" onclick="showCourseModal(${jsonToHTML})">
                                    <i class="fa fa-info"></i>
                                </button>
                                <button class="btn btn-primary" onclick="showEditCourseModal(${jsonToHTML})">
                                    <i class="fa fa-edit"></i>
                                </button>
                            `;
                        })

                        document.getElementById('auto-load').hidden=true;
                        stop = false;
                    }
                })
            }

        }

        //to check if this input consists of numbers and letters not just numbers
        function validateInput(inputElement) {
            // Remove any non-alphanumeric characters, including Arabic letters and spaces
            inputElement.value = inputElement.value.replace(/[^a-zA-Z0-9\u0600-\u06FF\s]/g, '');

            // Check if the input contains at least one letter
            var containsLetter = /[a-zA-Z\u0600-\u06FF]/.test(inputElement.value);

            // If the input doesn't contain a letter, clear the input
            if (!containsLetter) {
                inputElement.value = '';
            }
        }

        //to check if this input cosists of english letters and numbers only (doesn't accept arabic)
        function validateEngNumInput(inputElement) {

            var cleanedValue = inputElement.value.replace(/[^a-zA-Z0-9]/g, '');
            if (!/^[a-zA-Z0-9]{9}$/.test(cleanedValue)) {
                inputElement.value = cleanedValue;
            } else {
                inputElement.value = cleanedValue.slice(0, 9);
            }

        }

        //to check if this numbers only
        function validateNumInput(inputElement) {
            // Remove any non-numeric characters
            var cleanedValue = inputElement.value.replace(/\D/g, '');

            // Ensure the input is exactly 4 digits long
            if (/^\d{9}$/.test(cleanedValue)) {
                // Update the input value
                inputElement.value = cleanedValue;
            } else {
                // If input is not 4 digits, clear the input
                inputElement.value = cleanedValue.slice(0, 9);;
            }
        }

        //when add a new course
        addCourseForm.addEventListener("submit", (e) => {

            coursesLength = {!! json_encode($data, JSON_HEX_APOS) !!}.length;

            e.preventDefault();

            var serializedFormData = $('#addCourseForm').serialize();

            var if_submit = true;

            // Convert the serialized string to an array of objects (to take the inputs and check if they are empty)
            var formDataArray = serializedFormData.split('&').map(function(item) {
                var pair = item.split('=');
                return { name: pair[0], value: decodeURIComponent(pair[1] || '') };
            });

            //check if the inputs empty and if they empty givr them "input-error" class
            for (var i = 1; i < formDataArray.length; i++) {
                if(formDataArray[i].value==""){
                    var x = `#${formDataArray[i].name}`;
                    $(`${x}`).addClass('input-error');
                    if_submit = false;
                }
                if(document.getElementById('c_course_type').value==""){
                    $('#c_course_type').addClass('input-error');
                    if_submit = false;
                }
                if(document.getElementById('c_hours').value==""){
                    $('#c_hours').addClass('input-error');
                    if_submit = false;
                }
            }


            if(if_submit){

                data = $('#addCourseForm').serialize();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Send an AJAX request with the CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                // Send an AJAX request
                $.ajax({
                    beforeSend: function(){
                        $('#AddCourseModal').modal('hide');
                        $('#LoadingModal').modal('show');
                    },
                    type: 'POST',
                    url: "{{ route('admin.courses.create') }}",
                    data: data,
                    dataType: 'json',
                    success: function(response) {

                        //to show the search area if the added course is the first one
                        //because it's hidden when the table is empty
                        if(coursesLength==0){
                            document.getElementById('showSearch').hidden = false;
                        }

                        $('#LoadingModal').modal('hide');

                        $('#showTable').html(response.view);

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

        });

        //to remove red outline from required inputs
        $('#c_name').on('focus', function() {
    	    $('#c_name').removeClass('input-error');
        });
        $('#c_hours').on('focus', function() {
    	    $('#c_hours').removeClass('input-error');
        });
        $('#c_course_code').on('focus', function() {
    	    $('#c_course_code').removeClass('input-error');
        });
        $('#c_course_type').on('focus', function() {
    	    $('#c_course_type').removeClass('input-error');
        });
        $('#c_reference_code').on('focus', function() {
    	    $('#c_reference_code').removeClass('input-error');
        });
        $('#c_description').on('focus', function() {
    	    $('#c_description').removeClass('input-error');
        });

        //to remove red outline from required inputs
        $('#edit_c_name').on('focus', function() {
    	    $('#edit_c_name').removeClass('input-error');
        });
        $('#edit_c_hours').on('focus', function() {
    	    $('#edit_c_hours').removeClass('input-error');
        });
        $('#edit_c_course_code').on('focus', function() {
    	    $('#edit_c_course_code').removeClass('input-error');
        });
        $('#edit_c_course_type').on('focus', function() {
    	    $('#edit_c_course_type').removeClass('input-error');
        });
        $('#edit_c_reference_code').on('focus', function() {
    	    $('#edit_c_reference_code').removeClass('input-error');
        });
        $('#edit_c_description').on('focus', function() {
    	    $('#edit_c_description').removeClass('input-error');
        });

        //to empty the add modal any time it closed
        $("#AddCourseModal").on("hidden.bs.modal", function () {
            document.getElementById('c_name').value = "";
            document.getElementById('c_course_code').value = "";
            document.getElementById('c_hours').value = "";
            document.getElementById('c_course_type').value = "";
            document.getElementById('c_description').value = "";
            document.getElementById('c_reference_code').value = "";

            $('#c_name').removeClass('input-error');
            $('#c_hours').removeClass('input-error');
            $('#c_course_code').removeClass('input-error');
            $('#c_course_type').removeClass('input-error');
            $('#c_reference_code').removeClass('input-error');
            $('#c_description').removeClass('input-error');

            document.getElementById('similarCourseCodeMessage').hidden = true;
            document.getElementById('search_icon').hidden = true;
            document.getElementById('ok_icon').hidden = true;

            document.getElementById('similarCourseCodeRefMessage').hidden = true;
            document.getElementById('ref_search_icon').hidden = true;
            document.getElementById('ref_ok_icon').hidden = true;

            document.getElementById('add_course').disabled = false;

        });

        //to remove red outline when the edit modal any time it closed
        $("#EditCourseModal").on("hidden.bs.modal", function () {

            $('#edit_c_name').removeClass('input-error');
            $('#edit_c_hours').removeClass('input-error');
            $('#edit_c_course_code').removeClass('input-error');
            $('#edit_c_course_type').removeClass('input-error');
            $('#edit_c_reference_code').removeClass('input-error');
            $('#edit_c_description').removeClass('input-error');

            document.getElementById('edit_similarCourseCodeMessage').hidden = true;
            document.getElementById('edit_search_icon').hidden = true;
            document.getElementById('edit_ok_icon').hidden = true;

            document.getElementById('edit_similarCourseCodeRefMessage').hidden = true;
            document.getElementById('edit_ref_search_icon').hidden = true;
            document.getElementById('edit_ref_ok_icon').hidden = true;

            document.getElementById('edit_course').disabled = false;

        });


        function showEditCourseModal(data) {
            document.getElementById('edit_c_id').value = data.c_id;
            document.getElementById('edit_c_name').value = data.c_name;
            document.getElementById('edit_c_course_code').value = data.c_course_code;
            document.getElementById('edit_c_hours').value = data.c_hours;
            document.getElementById('edit_c_course_type').value = data.c_course_type;
            document.getElementById('edit_c_description').value = data.c_description;
            document.getElementById('edit_c_reference_code').value = data.c_reference_code;
            $('#EditCourseModal').modal('show');
        }

        function showCourseModal(data) {
            document.getElementById('show_c_name').value = data.c_name;
            document.getElementById('show_c_course_code').value = data.c_course_code;
            document.getElementById('show_c_hours').value = data.c_hours;
            document.getElementById('show_c_course_type').value = data.c_course_type;
            document.getElementById('show_c_description').value = data.c_description;
            document.getElementById('show_c_reference_code').value = data.c_reference_code;
            $('#ShowCourseModal').modal('show');
        }

        editCourseForm.addEventListener("submit", (e) => {

            e.preventDefault();

            data = $('#editCourseForm').serialize();

            var if_edit_submit = true;

            var serializedFormDataEdit = $('#editCourseForm').serialize();
            var formDataArrayEdit = serializedFormDataEdit.split('&').map(function(item) {
                var pair = item.split('=');
                return { name: pair[0], value: decodeURIComponent(pair[1] || '') };
            });

            for (var i = 1; i < formDataArrayEdit.length; i++) {
                if(formDataArrayEdit[i].value==""){
                    var x = `#edit_${formDataArrayEdit[i].name}`;
                    $(`${x}`).addClass('input-error');
                    if_edit_submit = false;
                }
            }


            if(if_edit_submit){
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Send an AJAX request with the CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                // Send an AJAX request
                $.ajax({
                    beforeSend: function(){
                        $('#EditCourseModal').modal('hide');
                        $('#LoadingModal').modal('show');
                    },
                    type: 'POST',
                    url: "{{ route('admin.courses.update') }}",
                    data: data,
                    dataType: 'json',
                    success: function(response) {

                        //to get the row that is clicked to update it
                        var course_id = formDataArrayEdit.find(item => item.name === 'c_id').value;
                        var row = document.querySelector('tr[data-id="' + course_id + '"]');

                        //update cells content
                        row.cells[1].textContent = response.data.c_name
                        row.cells[2].textContent = response.data.c_course_code
                        row.cells[3].textContent = response.data.c_hours
                        course_type = response.data.c_course_type;
                        row.cells[4].textContent = (course_type==0) ? "{{__('translate.Theoretical')}}" : (course_type==1) ? "{{__('translate.Practical')}}" : "{{__('translate.Theoretical - Practical')}}"

                        var jsonToHTML = JSON.stringify(response.data).replace(/"/g, "&quot;");
                        $(`#table_buttons_${course_id}`).html(`
                                <button class="btn btn-info" onclick="showCourseModal(${jsonToHTML})">
                                    <i class="fa fa-info"></i>
                                </button>
                                <button class="btn btn-primary" onclick="showEditCourseModal(${jsonToHTML})">
                                    <i class="fa fa-edit"></i>
                                </button>
                        `)

                        $('#EditCourseModal').modal('hide');


                    },
                    complete: function(){
                        $('#LoadingModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

        });

        function courseSearch(data) {

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            $('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>');


            $.ajax({
                url: "{{ route('admin.courses.courseSearch') }}",
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

        //to focus on the auto focus input
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });

        //to check course code and reference course code are exists
        function checkCourseCode(data,opp,page){

            //check for edit modal
            if(page=="edit"){

                document.getElementById('edit_ok_icon').hidden = true;
                document.getElementById('edit_ref_ok_icon').hidden = true;

                if(data!=""){

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })

                    $.ajax({
                        beforeSend: function(){
                            if(opp=="code"){
                                document.getElementById('edit_search_icon').hidden = false;
                            }else{
                                document.getElementById('edit_ref_search_icon').hidden = false;
                            }

                        },
                        url: "{{ route('admin.courses.checkCourseCode') }}",
                        method: "post",
                        data: {
                            'search': data,
                            'opp': opp,
                            _token: '{!! csrf_token() !!}',
                        },
                        success: function(response) {

                            if(response.data!=null){


                                if(opp=="code"){
                                    document.getElementById('edit_search_icon').hidden = true;
                                    document.getElementById('edit_ok_icon').hidden = true;
                                    document.getElementById('edit_similarCourseCodeMessage').hidden = false;
                                    document.getElementById('edit_course').disabled = true;
                                    edit_button_code = true;
                                }else{
                                    document.getElementById('edit_ref_search_icon').hidden = true;
                                    document.getElementById('edit_ref_ok_icon').hidden = true;
                                    document.getElementById('edit_similarCourseCodeRefMessage').hidden = false;
                                    document.getElementById('edit_course').disabled = true;
                                    edit_button_ref = true;
                                }


                            }else{

                                if(opp=="code"){
                                    document.getElementById('edit_similarCourseCodeMessage').hidden = true;
                                    document.getElementById('edit_search_icon').hidden = true;
                                    document.getElementById('edit_ok_icon').hidden = false;
                                    edit_button_code = false;
                                }else{
                                    document.getElementById('edit_similarCourseCodeRefMessage').hidden = true;
                                    document.getElementById('edit_ref_search_icon').hidden = true;
                                    document.getElementById('edit_ref_ok_icon').hidden = false;
                                    edit_button_ref = false;
                                }

                                if(edit_button_code == false && edit_button_ref == false){
                                    document.getElementById('edit_course').disabled = false;
                                }
                            }

                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                }
                else{
                    if(opp=="code"){
                        document.getElementById('edit_similarCourseCodeMessage').hidden = true;
                        document.getElementById('edit_search_icon').hidden = true;
                        document.getElementById('edit_ok_icon').hidden = true;
                    }else{
                        document.getElementById('edit_similarCourseCodeRefMessage').hidden = true;
                        document.getElementById('edit_ref_search_icon').hidden = true;
                        document.getElementById('edit_ref_ok_icon').hidden = true;
                    }

                    if(edit_button_code == false && edit_button_ref == false){
                        document.getElementById('edit_course').disabled = false;
                    }

                }

            }
            else{ //check for add modal

                document.getElementById('ok_icon').hidden = true;
                document.getElementById('ref_ok_icon').hidden = true;

                if(data!=""){

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })

                    $.ajax({
                        beforeSend: function(){
                            if(opp=="code"){
                                document.getElementById('search_icon').hidden = false;
                            }else{
                                document.getElementById('ref_search_icon').hidden = false;
                            }

                        },
                        url: "{{ route('admin.courses.checkCourseCode') }}",
                        method: "post",
                        data: {
                            'search': data,
                            'opp': opp,
                            _token: '{!! csrf_token() !!}',
                        },
                        success: function(response) {

                            if(response.data!=null){


                                if(opp=="code"){
                                    document.getElementById('search_icon').hidden = true;
                                    document.getElementById('ok_icon').hidden = true;
                                    document.getElementById('similarCourseCodeMessage').hidden = false;
                                    document.getElementById('add_course').disabled = true;
                                    add_button_code = true;
                                }else{
                                    document.getElementById('ref_search_icon').hidden = true;
                                    document.getElementById('ref_ok_icon').hidden = true;
                                    document.getElementById('similarCourseCodeRefMessage').hidden = false;
                                    document.getElementById('add_course').disabled = true;
                                    add_button_ref = true;
                                }


                            }else{

                                if(opp=="code"){
                                    document.getElementById('similarCourseCodeMessage').hidden = true;
                                    document.getElementById('search_icon').hidden = true;
                                    document.getElementById('ok_icon').hidden = false;
                                    add_button_code = false;
                                }else{
                                    document.getElementById('similarCourseCodeRefMessage').hidden = true;
                                    document.getElementById('ref_search_icon').hidden = true;
                                    document.getElementById('ref_ok_icon').hidden = false;
                                    add_button_ref = false;
                                }

                                if(add_button_code == false && add_button_ref == false){
                                    document.getElementById('add_course').disabled = false;
                                }
                            }

                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                }
                else{
                    if(opp=="code"){
                        document.getElementById('similarCourseCodeMessage').hidden = true;
                        document.getElementById('search_icon').hidden = true;
                        document.getElementById('ok_icon').hidden = true;
                    }else{
                        document.getElementById('similarCourseCodeRefMessage').hidden = true;
                        document.getElementById('ref_search_icon').hidden = true;
                        document.getElementById('ref_ok_icon').hidden = true;
                    }

                    if(add_button_code == false && add_button_ref == false){
                        document.getElementById('add_course').disabled = false;
                    }

                }
            }

        }



    </script>
@endsection
