<div class="modal fade show" id="add_branches_modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <form id="branch_create_form" action="{{ route('communications_manager_with_companies.follow_up_record.create_branches_ajax') }}" method="post">
                @csrf
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">اضافة فرع</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="height: 100px"></div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">عنوان الشركة</label>
                                        <input required type="text" class="form-control" id="branch_address">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">اختر مدير للشركة</label>
                                        <select class="form-control" name="" id="branch_manager">
                                            <option value="">اختر مدير للشركة ...</option>
                                            @foreach($users as $key)
                                                <option value="{{ $key->u_id }}">{{ $key->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">اختر مدينة</label>
                                        <select required class="form-control" name="" id="branch_city">
                                            <option value="">اختر مدينة ...</option>
                                            @foreach($cities as $key)
                                                <option value="{{ $key->id }}">{{ $key->city_name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">رقم الهاتف الاول</label>
                                        <input required type="text" class="form-control" id="branch_phone1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">رقم الهاتف الثاني</label>
                                        <input type="text" class="form-control" id="branch_phone2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12 text-center justify-content-lg-center align-content-center d-flex">
                                    <span style="font-size: 200px" class="fa fa-home"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="height: 100px"></div>
                </div>
                <div class="modal-footer ">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Close')}}{{-- إغلاق --}}</button>
                <button type="submit" class="btn btn-success">حفظ</button>
            </div>
            </form>
        </div>
    </div>
</div>
