<div class="modal fade show" id="create_cities_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <form action="{{ route('admin.cities.create') }}" method="post">
                @csrf
                <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">اسم المدينة باللغة العربية</label>
                                <input required type="text" name="city_name_ar" class="form-control" placeholder="اسم المدينة بالعربية">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">اسم المدينة باللغة الانجليزية</label>
                                <input required type="text" name="city_name_en" class="form-control" placeholder="اسم المدينة بالانجليزية">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">الوصف</label>
                                <textarea name="city_description" class="form-control" id="" cols="30" rows="2" placeholder="الوصف"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary">اضافة مدينة</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>

</div>
