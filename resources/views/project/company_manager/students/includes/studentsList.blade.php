@if ($students_company->isEmpty())
<h6 class="alert alert-danger">{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</h6>
@else
<div class="card">
    <table class="table table-bordered table-striped" id="students">
            <thead>
                <tr>
                    <th>{{__("translate.Student Name")}} {{-- اسم الطالب --}}</th>
                    <th>التدريب العملي</th>
                    <th>الفرع</th>
                    <th>القسم</th>
                    <th>{{__('translate.Operations')}} {{--  العمليات --}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students_company as $student_company)
                    <tr>
                        <td>
                            {{$student_company->users->name}}
                        </td>
                        <td>
                            {{$student_company->course->c_name}}
                        </td>
                        <td>
                            {{$student_company->branch->b_address ?? ''}}
                        </td>
                        <td>
                            @if (isset($student_company->department->d_name))
                                {{$student_company->department->d_name}}
                            @endif
                        </td>
                        <td>
                            <a href="{{route('company_manager.students.reports.index' , ['id' => $student_company->sc_student_id , 'student_company_id' => $student_company->sc_id])}}" class="btn btn-primary fa fa-file-text"></a>
                            <a href="{{route('company_manager.students.attendance.index' , ['id' => $student_company->sc_student_id , 'student_company_id' => $student_company->sc_id])}}" class="btn btn-primary fa fa-check"></a>
                            <a href="{{route('company_manager.students.payments.index' , ['id' => $student_company->sc_student_id , 'name_student' => $student_company->users->name , 'student_company_id' => $student_company->sc_id])}}" class="btn btn-primary fa fa-dollar"></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
