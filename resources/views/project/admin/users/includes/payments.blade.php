<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>{{__('translate.User who added payment')}} {{-- اسم المستخدم الذي قام بإضافة دفعة --}}</th>
            <th>{{__('translate.Payment Amount')}} {{-- قيمة المبلغ --}}</th>
            <th>{{__('translate.Reference Number')}} {{-- الرقم المرجعي --}}</th>
            <th>{{__('translate.Payment Status')}} {{-- حالة الدفعة --}}</th>
            <th>{{__('translate.Student Notes')}} {{-- ملاحظات الطالب --}}</th>
            <th>{{__('translate.Company Manager Notes')}} {{-- ملاحظات مدير الشركة --}}</th>
            <th>{{__('translate.Supervisor Notes')}} {{-- ملاحظات المشرف الأكاديمي --}}</th>
        </tr>
    </thead>
    <tbody>
@if ($payments->isEmpty())
<tr>
    <td colspan="7" class="text-center"><span>{{__('translate.This student has no payments')}}{{-- لا يوجد دفعات لهذا الطالب --}}</span></td>
</tr>
@else
            @foreach($payments as $payment)
                <tr>
                    {{-- <td>{{$payment->userInsertedById->name}}</td> --}}
                    <td><a href="{{route('admin.users.details',['id'=>$payment->userInsertedById->u_id])}}">{{$payment->userInsertedById->name}}</a></td>
                    <td>{{$payment->p_payment_value}} {{$payment->currency->c_symbol}}</td>
                    <td>{{$payment->p_reference_id}}</td>
                    <td>
                        @if ($payment->p_status == 1)
                            {{__('translate.Receipt Confirmed')}}{{-- تم تأكيد الاستلام --}}
                        @else
                            {{__("translate.Student hasn't confirmed receipt yet")}}{{-- لم يُؤكد الطالب استلامها --}}
                        @endif
                    </td>
                    <td>{{$payment->p_student_notes}}</td>
                    <td>{{$payment->p_company_notes}}</td>
                    <td>{{$payment->p_supervisor_notes}}</td>
                </tr>
            @endforeach
            @endif
        </tbody>
    </table>

