@if (auth()->user()->u_role_id != 10)
<a class=" col m-1 btn btn-primary btn-sm" href="{{route('admin.users.courses.student' , ['id'=>$user->u_id])}}">
    <h1 style="font-size: 25px; " class="fa fa-book" ></h1>
    <br>{{__('translate.Student Courses')}} {{-- التدريبات العملية للطالب --}}</a>
    <a class=" col m-1 btn btn-primary btn-sm" href="{{route('admin.users.places.training' , ['id'=>$user->u_id])}}">
        <h1 style="font-size: 25px; " class="fa fa-map-marker" ></h1>
        <br>
    {{__('translate.Training Places')}} {{-- أماكن التدريب --}}</a>
    <a class=" col m-1 btn btn-primary btn-sm" href="{{route('admin.users.students.attendance' , ['id'=>$user->u_id])}}">
        <h1 style="font-size: 25px; " class="fa fa-check-square" ></h1>
        <br>
    {{__('translate.Attendance Log')}} {{-- سجل المتابعة --}}</a>
    <a class="col m-1  btn btn-primary btn-sm" href="{{route('admin.users.student.payments' , ['id'=>$user->u_id])}}">
        <h1 style="font-size: 25px; " class="fa fa-dollar" ></h1>
        <br>
    {{__('translate.Payments')}} {{-- الدفعات --}}</a>
    <a class="col m-1  btn btn-primary btn-sm" href="{{route('admin.users.students.students_files' , ['id'=>$user->u_id])}}">
        <h1 style="font-size: 25px; " class="fa fa-file" ></h1>
        <br>
        ملفات</a>
    @else
    <a class=" col m-1 btn btn-primary btn-sm" href="{{route('admin.users.students.attendance' , ['id'=>$user->u_id])}}">
        <h1 style="font-size: 25px; " class="fa fa-check-square" ></h1>
        <br>
    {{__('translate.Attendance Log')}} {{-- سجل المتابعة --}}</a>
    <a class="col m-1  btn btn-primary btn-sm" href="{{route('admin.users.student.payments' , ['id'=>$user->u_id])}}">
        <h1 style="font-size: 25px; " class="fa fa-dollar" ></h1>
        <br>
    {{__('translate.Payments')}} {{-- الدفعات --}}</a>

@endif
