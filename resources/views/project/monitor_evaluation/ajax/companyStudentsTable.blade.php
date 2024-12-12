<div id="companyStudentsTable">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">{{__('translate.student_id')}}{{--رقم الطالب--}}</th>
                    <th scope="col">{{__('translate.Student Name')}}{{--اسم الطالب--}}</th>
                    <th scope="col">{{__('translate.student_major')}}{{--تخصص الطالب--}}</th>
                </tr>
            </thead>
            <tbody>
            @if ($data->isEmpty())
                <tr>
                    <td colspan="3" class="text-center"><span>{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                </tr>
            @else
                @foreach ($data as $key)
                    <tr>
                        <td>{{ $key->users->u_username }}</td>
                        <td>{{ $key->users->name }}</td>
                        <td>{{ $key->major }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        </table>
    </div>
</div>
