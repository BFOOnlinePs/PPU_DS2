<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>
            {{__('translate.Company Name')}}{{-- اسم الشركة --}}
        </th>
        {{-- <th class="text-center">حالة الطالب</th> --}}
{{--        <th>{{__('translate.capacity')}}</th>--}}
        <th class="text-center">{{--الطلاب في هذه الشركة--}} {{__("translate.Company's Interns")}}</th>
{{--        <th>{{__("translate.number_of_registered_students")}}</th>--}}
    </tr>
    </thead>
    <tbody>
    @if ($data->isEmpty())
        <tr>
            <td colspan="4" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
        </tr>
    @else
        @foreach ($data as $students_company)
            <tr>
                {{-- <td>{{$students_company->company->c_name}}</td> --}}
                <td @if(empty($students_company->company->c_status)) @if($students_company->c_status == 0) class="" @endif @else class="text-dark" @endif>
                    @if(app()->isLocale('en') || (app()->isLocale('ar') && empty($key->c_name)))
                        <div style="height: 20px;clear: both;float: right;margin-left: 20px;padding: 0 10px 0 10px" class="@if(empty($students_company->company->c_status)) @if($students_company->c_status == 0) bg-danger @endif @else bg-success @endif"><span>capacity</span></div> <a href="{{route("admin.companies.edit",['id'=>$students_company->sc_company_id ?? $students_company->c_id])}}">{{$students_company->company->c_name ?? $students_company->c_english_name}}</a>
                    @elseif(app()->isLocale('ar') || (app()->isLocale('en') && empty($key->c_english_name)))
                        <div style="height: 20px;clear: both;float: right;margin-left: 20px;padding: 0 10px 0 10px" class="@if(empty($students_company->company->c_status)) @if($students_company->c_status == 0) bg-danger @endif @else bg-success @endif"><span>capacity</span></div> <a href="{{route("admin.companies.edit",['id'=>$students_company->sc_company_id ?? $students_company->c_id])}}">{{$students_company->company->c_name ?? $students_company->c_english_name}}</a>
                    @endif
                </td>
{{--                <td>--}}
{{--                    {{ $students_company->company->c_capacity ?? $students_company->c_capacity }}--}}
{{--                </td>--}}
                {{-- <td class="text-center">
                    @if ($students_company->sc_status == 1)
                        <p class="badge badge-warning">ما زال يتدرب</p>
                        @elseif ($students_company->sc_status == 2)
                        <p class="badge badge-success">انتهى التدريب</p>
                        @elseif ($students_company->sc_status == 3)
                        <p class="badge badge-danger">محذوف</p>
                        @elseif ($students_company->sc_status == 4)
                        <p class="badge badge-info">مرفوض</p>
                    @endif
                </td> --}}
                <td class="text-center">
{{--                    <a href="{{route('communications_manager_with_companies.companies.students' , ['id'=>$students_company->sc_company_id])}}" class="btn btn-primary btn-xs"><span class="fa fa-users"></span></a>--}}
{{--                    @foreach($students_company->users as $key)--}}
{{--                        {{ $key->name }},--}}
{{--                    @endforeach--}}
                    <a class="btn btn-dark btn-sm col-md-8 text-white" href="{{ route('admin.users.details',['id'=>$students_company->sc_student_id]) }}">{{ $students_company->student->name }}</a>
                </td>
{{--                <td>--}}
{{--                     {{ $students_company->count }}--}}
{{--                </td>--}}
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

