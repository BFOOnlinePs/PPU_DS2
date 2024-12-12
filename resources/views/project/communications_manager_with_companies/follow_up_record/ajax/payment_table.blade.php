<table class="table table table-sm table-bordered">
    <thead>
    <tr>
        <th>{{ __('translate.Reference Number') }}</th>
        <th>{{ __('translate.student_name') }}</th>
        <th>{{ __('translate.Notes') }}</th>
        <th>{{ __('translate.added_by_user') }}</th>
        <th{{ __('translate.Payment Amount') }}</th>
        <th>{{ __('translate.Currency') }}</th>
    </tr>
    </thead>
    <tbody>
    @if($data->isEmpty())
        <tr>
            <td colspan="5" class="text-center">{{ __('translate.No available data') }}</td>
        </tr>
    @else
        @foreach($data as $key)
            <tr>
                <td>{{ $key->p_reference_id }}</td>
                <td>{{ $key->student->name }}</td>
                <td>{{ $key->p_student_notes }}</td>
                <td>{{ $key->inserted_by->name }}</td>
                <td class="text-center">{{ $key->p_payment_value }}</td>
                <td>{{ $key->currency->c_name }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
