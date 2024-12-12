<table class="table table-bordered table-striped" id="dataTable">
    <thead>
        <tr>
            <th>{{__('translate.University number and student name')}}{{-- الرقم الجامعي واسم الطالب --}}</th>
            <th>{{__('translate.Company')}}{{-- الشركة --}}</th>
            <th>{{__('translate.Arrival Time')}} {{-- وقت الوصول --}}</th>
            <th>{{__('translate.Leaving Time')}} {{-- وقت المغادرة --}}</th>
            <th>{{__('translate.View Details')}}{{-- عرض تفاصيل --}}</th>
        </tr>
    </thead>
    <tbody>
        @if ($data->isEmpty())
            <tr>
                <td colspan="5" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
            </tr>
        @else
            @foreach($data as $key)
                <tr>
                    <td>{{$key->student->name}} {{$key->student->u_username}}</td>
                    <td>{{$key->company->c_name}}</td>
                    <td>{{$key->sa_in_time}}</td>
                    <td>{{$key->sa_out_time}}</td>
                    <td>
                        <button class="btn btn-primary btn-xs" onclick="details({{$key->sa_id}})" type="button"><span class="fa fa-search"></span></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>


