<table class="table table-sm table-hover">
    <thead>
    <tr>
        <th>اسم الطالب</th>
        <th>العمليات</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key)
        <tr>
            <td>{{ $key->users->name }}</td>
            <td>
                @if (!empty($key->final_file))
                <a download="" href="{{ asset('public/storage/uploads/'.$key->final_file) }}" class="btn btn-xs btn-primary">تحميل التقرير</a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
