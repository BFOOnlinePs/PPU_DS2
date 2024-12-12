<table class="table table table-sm table-bordered">
    <thead>
    <tr>
        <th>{{ __('translate.Student Name') }}</th>
        <th>{{ __('translate.company_branch') }}</th>
        <th>{{ __('translate.follow_up_record') }}</th>
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
                <td>{{ $key->student->name }}</td>
                <td>{{ $key->company_branches->b_address ?? '' }}</td>
                <td>
                    <a target="_blank" href="{{ route('admin.users.students.attendance',['id'=> $key->student->u_id ]) }}" class="btn btn-primary btn-sm"><span class="fa fa-file"></span></a>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
