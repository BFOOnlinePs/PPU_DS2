    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{__("translate.Student Name")}} {{-- اسم الطالب --}}</th>
                <th>{{__('translate.User who added payment')}}{{-- اسم المستخدم الَّذي قام بإضافة دفعة --}}</th>
                <th>{{__('translate.Payment Amount')}} {{-- قيمة المبلغ --}}</th>
                <th>{{__('translate.Reference Number')}} {{-- الرقم المرجعي --}}</th>
                {{-- <th>العمليات</th> --}}
            </tr>
        </thead>
        <tbody>
            @if ($payments->isEmpty())
                <tr>
                    <td colspan="4" class="text-center"><span>{{__('translate.This student has no payments')}}{{-- لا يوجد دفعات لهذا الطالب --}}</span></td>
                </tr>
            @else
                @foreach($payments as $payment)
                    <tr>
                        <td>{{$payment->userStudent->name}}</td>
                        <td>{{$payment->userInsertedById->name}}</td>
                        <td>{{$payment->p_payment_value}} {{$payment->currency->c_symbol}}</td>
                        <td>{{$payment->p_reference_id}}</td>
                        {{-- <td>
                            @if ($payment->p_status == 0)
                                <a href="{{ route('company_manager.payments.update_status',['id'=>$payment->p_id])}}" class="btn btn-xs btn-primary">تاكيد عملة الدفع</a>
                            @endif
                        </td> --}}
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

