@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{--الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.surveys')}}{{--الاستبيانات --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('admin.survey.index') }}">{{__('translate.surveys')}}{{--الاستبيانات--}}</a>
@endsection

@section('content')

<div>
@if(auth()->user()->u_role_id != 2)  <button class="btn btn-primary  mb-2 btn-s" type="button" onclick='location.href="{{route("admin.survey.addSurvey")}}"'><span class="fa fa-plus"></span> {{__('translate.add_survey')}}{{-- إضافة استبيان --}}</button>@endif
</div>

<div class="card" style="padding-left:0px; padding-right:0px;">

    <div class="card-body" >
        @if(!$data->isEmpty())
        <div class="form-outline">
            <input type="search" onkeyup="surveySearch(this.value)" class="form-control mb-2" placeholder="{{__('translate.Search')}}"
                aria-label="Search" /> {{-- بحث --}}
        </div>
        @endif
        <div id="showTable">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col" style="display:none;">id</th>
                            <th scope="col">{{__('translate.survey_title')}} {{-- اسم الاستبيان --}}</th>
                            <th scope="col">{{__('translate.target_group')}}{{--  الفئة المستهدفة --}}</th>
                            <th scope="col">{{__('translate.added_by')}}{{-- منشئ الاستبيان  --}}</th>
                            <th scope="col">{{__('translate.start_date')}}{{-- تاريخ الظهور  --}}</th>
                            <th scope="col">{{__('translate.end_date')}} {{--  تاريخ الانتهاء --}}</th>
                            <th scope="col">{{__('translate.Operations')}} {{--  العمليات  --}}</th>

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
                                <td style="display:none;">{{ $key->s_id }}</td>
                                <td>{{$key->s_title}}</td>
                                <td>{{$key->targets->st_name}}</td>
                                <td>{{$key->s_added_by}} </td>
                                <td>{{$key->s_start_date}}</td>
                                <td>{{$key->s_end_date}}</td>
                                <td>
                                   <button class="btn btn-info"    onclick='location.href="{{route("admin.survey.surveyView",["id"=>$key->s_id])}}"'><i class="fa fa-info"></i></button>
                                   @if(auth()->user()->u_role_id == 1 || $key->users->u_role_id == auth()->user()->u_role_id)
                                   @if($key->s_start_date >  date('Y-m-d'))
                                   <button class="btn btn-primary" onclick='location.href="{{route("admin.survey.editSurvey",["id"=>$key->s_id])}}"'><i class="fa fa-edit"></i></button>@endif
                                   <button class="btn btn-primary" onclick='location.href="{{route("admin.survey.surveyResults",["id"=>$key->s_id])}}"'><i class="fa fa-briefcase"></i></button>
                                   <button class="btn btn-primary" onclick="showDeleteSurveyModal({{ $key->s_id }})"><i class="fa fa-trash"></i></button>
                                @endif
                                </td>
                          
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                </table>
            </div>
        </div>

    </div>


@include('project.admin.survey.modals.deleteModal')


</div>


@endsection


@section('script')
<script>
let s_id;

function surveySearch(data){

var csrfToken = $('meta[name="csrf-token"]').attr('content');

// Send an AJAX request with the CSRF token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrfToken
    }
})
$('#showTable').html('<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>')
$.ajax({
    url: "{{ route('admin.survey.surveySearch') }}",
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

function showDeleteSurveyModal(data) {
        s_id=data;
        $('#deleteModal').modal('show');
    }

function deleteSurvey(){
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
                    $('#deleteModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                type: 'POST',
                url: "{{ route('admin.survey.deleteSurvey') }}",
                data: {s_id},
                dataType: 'json',
                success: function(response) {
                    $('#showTable').html(response.view);
                   
                },
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    alert("nooo");
                    console.error(xhr.responseText);
                }
            });
    }


</script>
@endsection