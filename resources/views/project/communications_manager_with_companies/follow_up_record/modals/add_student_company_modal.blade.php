<div class="modal fade show" id="add_company_contact_modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header">
                <h5 class="modal-title">اضافة معلومات تواصل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">رقم التسجيل</label>
                            <select name="" id="">
                                <option value="">اختر رقم التسجيل</option>
                                @foreach($registration as $key)
                                    <option value="">{{  }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">الاسم الكامل</label>
                            <input type="text" id="name" class="form-control" placeholder="الاسم الكامل">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">الايميل</label>
                            <input onkeyup="check_email_found(this.value)" type="email" id="email" class="form-control" placeholder="البريد الالكتروني">
                            <p disabled="" id="validation_email" class="mt-1"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">كلمة المرور</label>
                            <input type="password" id="password" class="form-control" placeholder="كلمة المرور">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">رقم الهاتف الاول</label>
                            <input type="number" id="u_phone1" class="form-control" placeholder="رقم الهاتف الاول">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">رقم الهاتف الثاني</label>
                            <input type="number" id="u_phone2" class="form-control" placeholder="رقم الهاتف الثاني">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Close')}}{{-- إغلاق --}}</button>
                <button type="button" id="contact_company_save_button" onclick="create_contact_company()" class="btn btn-success">حفظ</button>
            </div>
        </div>
    </div>
</div>
