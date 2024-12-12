<table style="width: 100%;" class="table">
    <thead>
        <tr>
            <th style="width: 20px"></th>
            <th>اسم الطالب</th>
            <th>الفصل</th>
            <th>السنة</th>
        </tr>
    </thead>
    <tbody>
        @if($data->isEmpty())
            <tr>
                <td colspan="4" class="text-center">لا يوجد نتائج</td>
            </tr>
        @else
            @foreach($data as $key)
                <tr>
                    <td><button onclick="delete_nomination_table_ajax({{ $key->scn_id }})" class="btn btn-danger btn-sm"><span class="fa fa-close"></span></button></td>
                    <td>{{ $key->student->name }}</td>
                    <td>
                         @if($key->scn_semester == 1)
                            الفصل الاول
                        @elseif($key->scn_semester == 2)
                            الفصل الثاني
                        @elseif($key->scn_semester == 3)
                            الفصل الصيفي
                        @endif
                    </td>
                    <td>{{ $key->scn_year }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
