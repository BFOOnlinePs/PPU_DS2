<table class="table table-sm">
    <thead>
        <tr>
            <th>اسم الطالب</th>
            <th>حضور الطالب اليوم</th>
            <th>التاريخ</th>
        </tr>
    </thead>
    <tbody>
        @if($data->isEmpty())
            <tr>
                <td colspan="3" class="text-center">لا توجد بيانات</td>
            </tr>
        @else
            @foreach($data as $key)
                <tr>
                    <td>{{ $key->user->name }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
