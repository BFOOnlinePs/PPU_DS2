<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>{{__('translate.Company Name')}} {{-- اسم الشركة --}}</th>
            <th>{{--الطلاب في هذه الشركة--}} {{__("translate.Company's Interns")}}</th>
        </tr>
    </thead>
    <tbody>
        @if ($students_companies->isEmpty())
        <tr>
            <td colspan="2" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
        </tr>
        @else
            @foreach ($students_companies as $students_company)
                <tr>
                    {{-- <td>{{$students_company->company->c_name}}</td> --}}
                    <td><a href="{{route("admin.companies.edit",['id'=>$students_company->company->c_id])}}">{{$students_company->company->c_name}}</a></td>
                    <td><a href="{{route('communications_manager_with_companies.companies.students' , ['id'=>$students_company->sc_company_id])}}" class="btn btn-primary btn-xs"><span class="fa fa-users"></span></a></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

