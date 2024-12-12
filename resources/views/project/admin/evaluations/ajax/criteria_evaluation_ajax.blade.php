<table class="table table-sm table-hover">
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
            <td colspan="3" class="text-center">لا توجد بيانات</td>
        </tr>
    @else
        @foreach($data as $key)
            <tr>
                <td>{{ $key->criteria->c_criteria_name }}</td>
                <td>{{ $key->criteria->c_max_score }}</td>
                <td>
                    <button type="button" class="btn btn-xs btn-danger" onclick="delete_evaluation_criteria_ajax({{ $key->ec_id }})"><span class="fa fa-trash"></span></button>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
