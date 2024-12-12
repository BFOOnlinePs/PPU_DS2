<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>{{__('translate.User who added payment')}} {{-- اسم المستخدم الَّذي قام بإضافة دفعة --}}</th>
            <th>{{__('translate.Payment Amount')}} {{-- قيمة المبلغ --}}</th>
            <th>{{__('translate.Reference Number')}} {{-- الرقم المرجعي --}}</th>
            <th>{{__('translate.Payment Status')}}{{-- حالة الدفعة --}}</th>
            <th>{{__('translate.Company Manager Notes')}}{{-- ملاحظات مدير الشركة --}}</th>
            <th>{{__('translate.Supervisor Notes')}}{{-- ملاحظات المشرف الأكاديمي --}}</th>
            <th>{{__('translate.Student Notes')}}{{-- ملاحظات الطالب --}}</th>
            <th>{{__('translate.Confirm Payment Receipt')}}{{-- تأكيد استلام الدفعة --}}</th>
        </tr>
    </thead>
    <tbody>
        @if ($payments->isEmpty())
        <tr>
            <td colspan="8" class="text-center"><span>{{__('translate.This student has no payments')}}{{-- لا يوجد دفعات لهذا الطالب --}}</span></td>
        </tr>
        @else
            @foreach($payments as $payment)
                <tr>
                    <td>{{$payment->userInsertedById->name}}</td>
                    <td>{{$payment->p_payment_value}} {{$payment->currency->c_symbol}}</td>
                    <td>{{$payment->p_reference_id}}</td>
                    <td>
                        @if ($payment->p_status == 1)
                            {{__('translate.Receipt Confirmed')}}{{-- تم تأكيد الاستلام --}}
                        @else
                            {{__("translate.Student hasn't confirmed receipt yet")}}{{-- لم يُؤكد الطالب استلامها --}}
                        @endif
                    </td>
                    <td title="{{$payment->p_company_notes}}" onclick="showAlert(this , '{{__('translate.Company Manager Notes')}}')" style="cursor: pointer; font-size: smaller;">
                        @php
                            $notes = $payment->p_company_notes;
                            $truncated_notes = str_word_count($notes, 1);
                            $truncated_notes = count($truncated_notes) > 7 ? implode(' ', array_slice($truncated_notes, 0, 7)) . ' ...' : $notes;
                        @endphp
                        {{$truncated_notes}}
                    </td>
                    <td title="{{$payment->p_supervisor_notes}}" onclick="showAlert(this , '{{__('translate.Supervisor Notes')}}')" style="cursor: pointer; font-size: smaller;">
                        @php
                            $supervisorNotes = $payment->p_supervisor_notes;
                            $truncatedSupervisorNotes = str_word_count($supervisorNotes, 1);
                            $truncatedSupervisorNotes = count($truncatedSupervisorNotes) > 7 ? implode(' ', array_slice($truncatedSupervisorNotes, 0, 7)) . ' ...' : $supervisorNotes;
                        @endphp
                        {{$truncatedSupervisorNotes}}
                    </td>
                    <td title="{{$payment->p_student_notes}}" onclick="showAlert(this , '{{__('translate.Student Notes')}}')" style="cursor: pointer; font-size: smaller;">
                        @php
                            $studentNotes = $payment->p_student_notes;
                            $truncatedStudentNotes = str_word_count($studentNotes, 1);
                            $truncatedStudentNotes = count($truncatedStudentNotes) > 7 ? implode(' ', array_slice($truncatedStudentNotes, 0, 7)) . ' ...' : $studentNotes;
                        @endphp
                        {{$truncatedStudentNotes}}
                    </td>
                    @if ($payment->p_status == 1)
                        <td><span>{{__('translate.Receipt Confirmed')}}{{-- تم تأكيد الاستلام --}}</span></td>
                    @else
                        <td><button class="btn btn-primary btn-sm" onclick="showModal({{$payment->p_id}})" type="button" id="confirm_payment_btn_{{$payment->p_id}}"><span class="fa fa-plus"></span>{{__('translate.Confirm Payment Receipt')}}{{-- تأكيد استلام الدفعة --}}</button></td>
                    @endif
                </tr>
            @endforeach
            @endif
    </tbody>
</table>

