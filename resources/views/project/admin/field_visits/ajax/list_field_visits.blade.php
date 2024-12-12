<table class="table table-sm table-hover">
    <thead>
    <tr>
        <th>الطالب</th>
        <th>المشرف</th>
        <th>الشركة</th>
        <th>عنوان الزيارة</th>
        <th>وقت الزيارة</th>
        <th>ملاحظات</th>
        <th>العمليات</th>
    </tr>
    </thead>
    <tbody>
    @if($data->isEmpty())
        <tr>
            <td colspan="7" class="text-center">لا توجد مراسلات</td>
        </tr>
    @else
        @foreach($data as $key)
            <tr>
                <td>{{ implode(', ', $key->student_names) }}</td>
                <td>{{ $key->supervisor->name }}</td>
                <td>{{ $key->company->c_name }}</td>
                <td>{{ $key->fv_visiting_place }}</td>
                <td>{{ $key->fv_vistit_time }}</td>
                <td>{{ $key->fv_notes }}</td>
                <td>
                    <a href="{{ route('training_supervisor.field_visits.details',['id'=>$key->fv_id]) }}" class="btn btn-primary btn-sm"><span class="fa fa-search"></span></a>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
