<table>
    <thead>
        <tr>
            <th>اسم الطالب</th>
            <th>اسم التدريب</th>
            <th>اسم المشرف</th>
            <th>اسم الشركة</th>
            <th>التاريخ</th>
            <th>تقييم المشرف</th>
            <th>تقييم الشركة</th>
            <th>العلامة النهائية</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key)
            <tr>
                <td>{{ $key->users->name }}</td>
                <td>{{ $key->courses->c_name }}</td>
                <td>{{ $key->supervisor->name }}</td>
                <td>{{ $key->studentCompany->company->c_name }}</td>
                <td>{{ $key->created_at }}</td>
                <td>{{ $key->university_score }}</td>
                <td>{{ $key->company_score }}</td>
                <td>100 / {{ $key->total_score ?? $key->university_score + $key->company_score }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
