<table class="table table-sm">
    <thead>
    <tr>
        <th></th>
        <th>السنة</th>
        <th>الفصل</th>
        <th>عدد الذكور</th>
        <th>عدد الاناث</th>
        <th>المجموع الكلي</th>
    </tr>
    </thead>
    <tbody>
    @if($results->isEmpty())
        <tr>
            <td colspan="6" class="text-center">لا توجد نتائج</td>
        </tr>
    @else
        @foreach($results as $key)
            <tr>
                <td>{{ $key->course_name }}</td>
                <td>{{ $key->r_year }}</td>
                <td>
                    @if($key->r_semester == '1')
                        فصل اول
                    @else
                        فصل ثاني
                    @endif
                </td>
                <td>{{ $key->male_count }}</td>
                <td>{{ $key->female_count }}</td>
                <td>{{ $key->total_count }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
