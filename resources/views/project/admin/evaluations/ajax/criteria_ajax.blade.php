<table class="table table-hover table-sm">
    <thead>
        <tr>
            <th>اسم المعيار</th>
            <th>علامة المعيار</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if($data->isEmpty())
            <tr>
                <td colspan="2" class="text-center">لا توجد بيانات</td>
            </tr>
        @else
            @foreach($data as $key)
                <tr>
                    <td>{{ $key->c_criteria_name }}</td>
                    <td>{{ $key->c_max_score }}</td>
                    <td>
                        <button type="button" class="btn btn-xs btn-success" onclick="add_evaluation_criteria_ajax({{ $key->c_id }})"><span class="fa fa-plus"></span></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
