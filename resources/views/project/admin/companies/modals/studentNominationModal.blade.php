<div class="modal fade show" id="AddStudentNominationModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <span style="font-size: 22px">اقتراح طلاب</span>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input onkeyup="search_student_ajax()" class="form-control search_student" type="text" placeholder="بحث عن طالب">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select onchange="search_student_ajax()" class="js-example-basic-single" name="" id="major_id">
                                            <option value="">جميع التخصصات</option>
                                            @foreach($majors as $key)
                                                <option value="{{ $key->m_id }}">{{ $key->m_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div style="height: 400px;overflow: scroll" id="search_student_table">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="overflow: scroll;height: 400px" id="student_nomination_table">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
{{--                    <button type="submit" class="btn btn-primary">{{__('translate.Add Branch')}}--}}{{-- إضافة فرع --}}{{--</button>--}}
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                </div>
        </div>
    </div>

</div>
