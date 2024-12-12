<div class="modal fade show" id="AddPaymentModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" >
                            <h1><span class="fa fa-clipboard" style="text-align: center; font-size:80px; "></span></h1>
                            <h6>
                                @if(app()->getLocale() == 'en')
                                    {{__('translate.Add Payment to')}} {{$name_student}}{{-- إضافة دفعة ل --}}
                                @else
                                    {{__('translate.Add Payment to')}}{{$name_student}}{{-- إضافة دفعة ل --}}
                                @endif
                            </h6>
                            <hr>
                            <p>{{__('translate.In this section, you can add payment for a student')}}{{-- في هذا القسم يمكنك إضافة دفعة لطالب --}}</p>
                        </div>
                        <div class="col-md-8">
                            <form class="form-horizontal" id="addPaymentsForm" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <input type="hidden" name="student_company_id" id="student_company_id" value="{{$student_company_id}}">
                                <input type="hidden" name="p_student_id" id="p_student_id" value="{{$id}}">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Payment Amount')}} {{-- قيمة المبلغ --}}</label>
                                            <input type="number" step="0.01" name="p_payment_value" id="p_payment_value" class="form-control">
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Currency')}}{{-- العملة --}}</label>
                                            <select autofocus class="js-example-basic-single col-sm-12" name="p_currency_id" id="p_currency_id">
                                                @foreach ($currencies as $currency)
                                                    <option value="{{$currency->c_id}}">{{$currency->c_name}} {{$currency->c_symbol}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Reference Number')}} {{-- الرقم المرجعي --}}</label>
                                            <input type="text" name="p_reference_id" id="p_reference_id" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-5">
                                        <div class="mb-3 row">
                                            <label for="p_file">{{__('translate.Attachment File')}} {{-- الملف المرفق --}}</label>
                                            <input type="file" name="p_file" id="p_file" class="form-control">
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Notes')}}{{-- ملاحظات --}}</label>
                                            <textarea name="p_company_notes" id="p_company_notes" cols="30" rows="5" class="form-control"></textarea>
                                        </div>
                                        <div class="mb-3 row">

                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary">{{__('translate.Add Payment')}}{{-- إضافة دفعة --}}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

