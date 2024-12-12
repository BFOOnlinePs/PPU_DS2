<table class="table table table-sm table-bordered">
    <thead>
        <tr>
            <th>{{ __('translate.company_branch_address') }}</th>
            <th>{{ __('translate.phone_number') }}</th>
            <th>{{ __('translate.branch_manager_number') }}</th>
        </tr>
    </thead>
    <tbody>
        @if($data->isEmpty())
            <tr>
                <td colspan="3" class="text-center">{{ __('translate.No available data') }}</td>
            </tr>
        @else
            @foreach($data as $key)
                <tr>
                    <td>{{ $key->company->c_name }}</td>
                    <td>{{ $key->b_phone1 }}</td>
                    <td>{{ $key->user->name }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
