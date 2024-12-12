<table class="table table-bordered">
    <thead>
        <tr>
            <th>{{ __('translate.student_name') }}</th>
            <th>{{ __('translate.company_name') }}</th>
            <th>{{ __('translate.in_time') }}</th>
            <th>{{ __('translate.out_time') }}</th>
            <th>{{ __('translate.Reports') }}</th>
        </tr>
    </thead>
    <tbody>
        @if($data->isEmpty())
            <tr>
                <th colspan="4" class="text-center">لا توجد نتائج</th>
            </tr>
        @else
            @foreach($data as $key)
                <tr>
                    <td>{{ $key->user->name }}</td>
                    <td>{{ $key->company->c_name }}</td>
                    <td>{{ $key->sa_in_time }}</td>
                    <td>{{ $key->sa_out_time }}</td>
                    <td>
                        @if(empty($key->report_attendance->sr_report_text))
                            {{ __('translate.empty') }}
                        @else
                            <button onclick="show_report_from_modal('{{ $key->report_attendance->sr_report_text }}')" class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#show_report_for_attendance_modal"><span class="fa fa-file"></span></button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
