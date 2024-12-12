<div id="trainingHoursTable">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" style="display:none;">id</th>
                    <th scope="col">{{__('translate.student_id')}}{{--رقم الطالب--}}</th>
                    <th scope="col">{{__('translate.Student Name')}}{{--اسم الطالب--}}</th>
                    <th scope="col">{{__('translate.total_training_hours')}}{{--تخصص الطالب--}}</th>
                </tr>
            </thead>
            <tbody>
                @if ($data->isEmpty())
                <tr>
                    <td colspan="6" class="text-center"><span>{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                </tr>
                @else
                    @foreach ($data as $key)
                        <tr>
                            <td style="display:none;">{{ $key->r_id }}</td>
                            <td>{{ $key->users->u_username }}</td>
                            <td>{{ $key->users->name }}</td>
                            <td>{{ $key->trainingHoursTotal }}ساعات,{{ $key->trainingMinutesTotal }}دقائق</td>

                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td>{{__('translate.total_training_hours')}}{{--إجمالي ساعات التدريب--}}</td>
                    <td>
                        {{$totalHours}}{{--ساعات--}}{{__('translate.Hours')}} ، {{$totalMinutes}}{{--دقائق--}} {{__('translate.Minutes')}}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
