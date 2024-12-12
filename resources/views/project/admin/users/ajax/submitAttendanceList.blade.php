@if ($student_attendances->isEmpty())
    <h6 class="alert alert-danger">{{__('translate.No data to display')}}{{-- لا يوجد سجلات لعرضها --}}</h6>
@else
<div class="container-fluid">
    <div class="row ui-sortable" id="draggableMultiple">
        @foreach($student_attendances as $student_attendance)
            <div class="col-sm-12 col-xl-6">
                <div class="card b-r-0">
                    <div class="card-header pb-0">
                        <a href="{{route('students.report.edit' , ['sa_id' => $student_attendance->sa_id])}}" class="fa fa-edit" style="font-size: x-large;" data-bs-original-title="" title=""><span></span></a>
                        <h6>{{strftime('%A', strtotime($student_attendance->sa_in_time))}}</h6>
                        <h6>{{date("Y-m-d", strtotime($student_attendance->sa_in_time))}}</h6>
                    </div>
                    <div class="card-body">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif


