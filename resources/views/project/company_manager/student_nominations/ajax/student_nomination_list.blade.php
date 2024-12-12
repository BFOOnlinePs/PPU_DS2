<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <td>{{ __('translate.Student Name') }}</td>
            <td>{{ __('translate.cv') }}</td>
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
                    <td>{{ $key->student->name }}</td>
                    <td>
                        <a href="" download="cv_{{ $key->u_cv }}" class="btn btn-info btn-sm"><span class="fa fa-download"></span></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
