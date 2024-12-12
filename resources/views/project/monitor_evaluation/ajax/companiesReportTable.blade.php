<div id="companiesReportTable">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" style="display:none;">id</th>
                    <th scope="col">{{__('translate.Company Name')}} {{-- اسم الشركة --}}</th>
                    <th scope="col">{{__('translate.Company Manager')}}{{-- مدير الشركة --}}</th>
                    <th scope="col">{{__('translate.Company Category')}}{{-- تصنيف الشركة --}}</th>
                    <th scope="col">{{__('translate.Company Type')}}{{-- نوع الشركة --}}</th>

                    <th scope="col">{{__('translate.Total Students')}}{{-- إجمالي الطلاب--}} </th>
                    <th scope="col">{{__('translate.display_students')}}{{--استعراض الطلاب--}}</th>


                </tr>
            </thead>
            <tbody>
            @if ($data->isEmpty())
                <tr>
                    <td colspan="6" class="text-center"><span>{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                </tr>
            @else
                @foreach ($data as $key)
                    <tr>
                        <td style="display:none;">{{ $key->c_id }}</td>
                        <td>{{ $key->c_name }}</td>
                        <td>{{ $key->manager->name }}</td>
                        <td>{{ $key->companyCategories->cc_name}}</td>
                        @if( $key->c_type == 1) <td>{{__('translate.Public Sector')}}{{-- قطاع عام --}}</td>@endif
                        @if( $key->c_type == 2) <td>{{__('translate.Private Sector')}}{{-- قطاع خاص --}}</td>@endif
                        <td>
                          {{$key->studentsTotal}}
                        </td>
                        <td>
                            {{-- <button class="btn btn-primary" onclick='location.href="{{route("monitor_evaluation.companyStudentsReport")}}"'><i class="fa fa-search"></i></button> --}}
                            <button class="btn btn-primary" onclick='location.href="{{route("monitor_evaluation.companyStudentsReport",["id"=>$key->c_id])}}"'><i class="fa fa-search"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        </table>
    </div>
</div>
