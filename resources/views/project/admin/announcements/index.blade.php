@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{--الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.announcements')}}{{--اعلانات--}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('admin.announcements.index') }}">{{__('translate.announcements')}}{{--اعلانات--}}</a>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection

@section('content')

<div>
<button class="btn btn-primary  mb-2 btn-s" type="button" onclick="showAddAnnouncementsModal()"><span class="fa fa-plus"></span> {{__('translate.add_announcement')}}{{-- إضافة اعلان --}}</button>
</div>

<div class="card" style="padding-left:0px; padding-right:0px;">

    <div class="card-body" >
        @if(!$data->isEmpty())
        <div class="form-outline">
            <input type="search" onkeyup="announcementSearch(this.value)" class="form-control mb-2" placeholder="{{__('translate.Search')}}"
                aria-label="Search" /> {{-- بحث --}}
        </div>
        @endif
        <div id="showTable">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col" style="display:none;">id</th>
                            <th scope="col">{{__('translate.announcement_title')}} {{-- عنوان الاعلان --}}</th>
                            <th scope="col">{{__('translate.a_added_by')}}{{-- منشئ الاعلان  --}}</th>
                            <th scope="col">{{__('translate.announcement_stutas')}}{{-- حالة الاعلان --}}</th>
                            <th scope="col">{{__('translate.Operations')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($data->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
                        </tr>
                    @else
                        @foreach($data as $key)
                            <tr>
                                <td style="display:none;">{{ $key->a_id }}</td>
                                <td>{{$key->a_title}}</td>
                                <td>{{$key->users->name}} </td>
                                <td> <select class="js-example-basic-single col-sm-12" name="a_status_{{$key->a_id}}" id="a_status_{{$key->a_id}}" @if($key->users->u_role_id == auth()->user()->u_role_id || auth()->user()->u_role_id ==1) onchange="changeAnnouncementStutas({{$key}})" @endif>
                                        <option @if($key->a_status==1) selected  @endif value="1">مفعل</option>
                                        <option @if($key->a_status==0) selected  @endif value="0">غير مفعل</option>
                                     </select></td>
                                <td><button class="btn btn-info" onclick='location.href="{{route("admin.announcements.edit",["id"=>$key->a_id])}}"'><i class="fa fa-info"></i></button>
                                @if($key->users->u_role_id == auth()->user()->u_role_id || auth()->user()->u_role_id ==1)<button class="btn btn-primary" onclick="editAnnouncement({{ $key }})"><i class="fa fa-edit"></i></button></td>@endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                </table>
            </div>
        </div>

    </div>


@include('project.admin.announcements.modals.addAnnouncements')
@include('project.admin.announcements.modals.showAnnouncementsModal')
@include('project.admin.announcements.modals.editAnnouncementsModal')
@include('layouts.loader')


</div>


@endsection


@section('script')

<script>
let s_id;


let addAnnouncementsForm=document.getElementById('addAnnouncementsForm');
let editAnnouncementsForm=document.getElementById('editAnnouncementsForm');

function announcementSearch(data){

var csrfToken = $('meta[name="csrf-token"]').attr('content');

// Send an AJAX request with the CSRF token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrfToken
    }
})
$('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3"></div></div></div>')
$.ajax({
    url: "{{ route('admin.announcements.announcementSearch') }}",
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

function showAddAnnouncementsModal(){

    $('#AddAnnouncementsModal').modal('show');


}

    addAnnouncementsForm.addEventListener("submit", (e) => {
            e.preventDefault();
            //data = $('#addCourseToSemesterForm').serialize();
            let photo = document.getElementById('a_image').files[0];
            let formData = new FormData();
            formData.append('a_title' , document.getElementById('a_title').value);
            formData.append('a_content', document.getElementById('a_content').value);
            formData.append('a_image', photo);

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
                    $('#AddAnnouncementsModal').modal('hide');
                    $('#AddAnnouncementsModal').on('show.bs.modal', function(e) {
                      $('#addAnnouncementsForm')[0].reset();
                    });
                    $('#LoadingModal').modal('show');
                },
                type: 'POST',
                url: "{{ route('admin.announcements.create') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {

                    $('#showTable').html(response.view);


                },
                complete: function(){
                    $('#LoadingModal').modal('hide');
                    $('#AddAnnouncementsModal').modal('hide');

                },
                error: function(xhr, status, error) {
                    alert("nooo");
                    console.error(xhr.responseText);
                }
            });

    });
function showAnnouncement(data){
    var aImageSrc = "{{ asset('../storage/app/public/uploads/announcements/') }}" + '/' + data.a_image;
   document.getElementById('show_a_title').value=data.a_title;
   document.getElementById('show_a_image').src = aImageSrc;
   document.getElementById('show_a_content').value=data.a_content;
   $('#showAnnouncementsModal').modal('show');



}
function editAnnouncement(data){
    var aImageSrc = "{{ asset('../storage/app/public/uploads/announcements/') }}" + '/' + data.a_image;
   document.getElementById('edit_a_id').value=data.a_id;
   document.getElementById('edit_a_title').value=data.a_title;
   document.getElementById('edit_a_image').src = aImageSrc;
   document.getElementById('edit_a_content').value=data.a_content;
   $('#editAnnouncementsModal').modal('show');



}
editAnnouncementsForm.addEventListener("submit", (e) => {
            e.preventDefault();
            //data = $('#addCourseToSemesterForm').serialize();
            let photo = document.getElementById('edit_a_image').files[0];
            let formData = new FormData();
            formData.append('a_id' , document.getElementById('edit_a_id').value);
            formData.append('a_title' , document.getElementById('edit_a_title').value);
            formData.append('a_content', document.getElementById('edit_a_content').value);
            formData.append('a_image', photo);

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
                    $('#editAnnouncementsModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                type: 'POST',
                url: "{{ route('admin.announcements.update') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {

                    $('#showTable').html(response.view);


                },
                complete: function(){
                    $('#LoadingModal').modal('hide');
                    $('#editAnnouncementsModal').modal('hide');

                },
                error: function(xhr, status, error) {
                    alert("nooo");
                    console.error(xhr.responseText);
                }
            });

    });
function changeAnnouncementStutas(data){
        const selectElement = document.getElementById("a_status_"+data.a_id).value;
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

                    $('#LoadingModal').modal('show');
                },
            type: 'POST',
            url:"{{ route('admin.announcements.updateStutas') }}",
            data: {
                selected_a_id:data.a_id,
                selected_a_status:selectElement
            },
            dataType: 'json',
            success: function (response) {

            $('#showTable').html(response.view);
            },
            complete: function(){
                $('#LoadingModal').modal('hide');

                },
            error: function (xhr, status, error) {
                console.error("error" + error);
            }
        });

}
// function showDeleteSurveyModal(data) {
//         s_id=data;
//         $('#deleteModal').modal('show');
//     }

// function deleteSurvey(){
//             var csrfToken = $('meta[name="csrf-token"]').attr('content');

//             // Send an AJAX request with the CSRF token
//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': csrfToken
//                 }
//             });

//             // Send an AJAX request
//             $.ajax({
//                 beforeSend: function(){
//                     $('#deleteModal').modal('hide');
//                     $('#LoadingModal').modal('show');
//                 },
//                 type: 'POST',
//                 url: "{{ route('admin.survey.deleteSurvey') }}",
//                 data: {s_id},
//                 dataType: 'json',
//                 success: function(response) {
//                     $('#showTable').html(response.view);

//                 },
//                 complete: function(){
//                     $('#LoadingModal').modal('hide');
//                 },
//                 error: function(xhr, status, error) {
//                     alert("nooo");
//                     console.error(xhr.responseText);
//                 }
//             });
//     }


</script>
@endsection
