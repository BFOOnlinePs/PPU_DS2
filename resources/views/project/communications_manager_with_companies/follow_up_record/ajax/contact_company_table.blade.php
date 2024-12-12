<table class="table table-sm table-hover table-bordered">
    <thead>
        <tr>
            <th>{{ __('translate.Supervisor') }}</th>
            <th>{{ __('translate.Email') }}</th>
            <th>{{ __('translate.phone_number') }}</th>
            <th>{{ __('translate.Operations') }}</th>
        </tr>
    </thead>
    <tbody>
        @if($data->isEmpty())
            <tr>
                <td colspan="4" class="text-center">{{ __('translate.No available data') }}</td>
            </tr>
        @else
            @foreach($data as $key)
                <tr>
                    <td>{{ $key->name }}</td>
                    <td>{{ $key->email }}</td>
                    <td>{{ $key->u_phone1 }}</td>
                    <td>
                        <button onclick="delete_contact_company({{ $key->u_id }})" class="btn btn-danger btn-sm"><span class="fa fa-times"></span></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
