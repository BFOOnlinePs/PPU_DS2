@if ($students_companies->isEmpty())
    <h6 class="alert alert-danger">{{__('translate.No data to display')}}{{--لا يوجد شركات لعرضها--}}</h6>
@else
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{__('translate.Company Name')}} {{-- اسم الشركة --}}</th>
                <th>{{__("translate.Supervisor Students in this Company")}}{{-- طلاب المشرف في هذه الشركة --}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students_companies as $students_company)
                <tr>
                    {{-- <td>{{$students_company->company->c_name}}</td> --}}
                    <td><a href="{{route("admin.companies.edit",['id'=>$students_company->company->c_id])}}">{{$students_company->company->c_name}}</a></td>
                    <td><a href="{{route('supervisor_assistants.companies.students' , ['id'=>$students_company->sc_company_id])}}" class="btn btn-primary btn-xs"><span class="fa fa-users"></span></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

