<table class="table table-sm table-hover">
    <thead>
    <tr>
        <th>اسم الطالب</th>
        {{-- <th>الشركة</th> --}}
        <th>العمليات</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key)
        <tr>
            <td>{{ $key->users->name }}</td>
            {{-- <td>{{ $key->company->c_name }}</td> --}}
            <td>
                <a href="{{ route('admin.users.details',['id'=>$key->users->u_id]) }}" class="btn btn-xs btn-primary"><span class="fa fa-search"></span></a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
