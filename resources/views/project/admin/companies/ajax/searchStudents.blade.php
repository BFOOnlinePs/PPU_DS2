<table class="table">
    <thead>
        <tr>
            <th style="width: 20px"></th>
            <th>اسم الطالب</th>
        </tr>
    </thead>
    <tbody>
    @if($data->isEmpty())
        <tr>
            <td colspan="2" class="text-center">لا توجد نتائج</td>
        </tr>
    @else
        @foreach($data as $key)
            <tr>
                <td>
                    <input onchange="add_nomination_table_ajax({{ $key }})" type="checkbox">
                </td>
                <td>
                    {{ $key->u_username }} - {{ $key->name }}
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
