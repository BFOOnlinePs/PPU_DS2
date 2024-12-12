<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>{{ __('translate.student_name') }}</th>
            <th>{{ __('translate.company_name') }}</th>
            <th>{{ __('translate.cv') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @if($data->isEmpty())
        <tr>
            <td colspan="4" class="text-center">لا توجد بيانات</td>
        </tr>
    @else
        @foreach($data as $key)
            <tr>
                <td>{{ $key->student->name }}</td>
                <td>{{ $key->company->c_name }}</td>
                <td>
                    <a class="btn btn-info btn-sm" href=""><span class="fa fa-download"></span></a>
                </td>
                <td>
                    <button onclick="change_status_from_cv({{ $key->student->u_id }} , 1)" class="btn btn-success btn-sm"><span class="fa fa-check"></span></button>
                    <button onclick="change_status_from_cv({{ $key->student->u_id }} , 2)" class="btn btn-danger btn-sm"><span class="fa fa-close"></span></button>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
