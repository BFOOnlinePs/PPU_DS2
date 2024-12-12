<div class="modal fade show" id="AddCourseModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">

                        <div class="col-md-4 text-center" style="margin:auto">


                                <h1><span class="fa fa-plus" style="text-align: center; font-size:80px; "></span></h1>


                                <h1>{{__('translate.Add Course')}}{{-- إضافة تدريب عملي --}}</h1>

                                <hr>
                                <p>{{__('translate.In this section, you can add a new course')}}{{-- في هذا القسم يمكنك إضافة تدريب عملي جديد --}}</p>


                        </div>

                        <div class="col-md-8">
                            <form class="form-horizontal" id="addCourseForm" action="" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Course Name')}} {{-- اسم التدريب العملي --}}<span style="color: red">*</span></label>
                                            <div class="col-lg-12">
                                                <input id="c_name" name="c_name" type="text"
                                                    class="form-control " autofocus oninput="validateInput(this)">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Course Code')}}{{-- رمز التدريب العملي --}}<span style="color: red">*</span></label>
                                            <div class="input-container">
                                                <i id="ok_icon" class="icon fa fa-check" style="color:#ef681a" hidden></i>
                                                <i id="search_icon" class="icon_spinner fa fa-spin fa-refresh" hidden></i>
                                                <input class="form-control" type="text" id="c_course_code" name="c_course_code" onkeyup="checkCourseCode(this.value,'code')" oninput="validateNumInput(this)">
                                            </div>

                                            <div id="similarCourseCodeMessage" style="color:#dc3545" hidden>
                                                <span>{{__('translate.There is course with the same code entered')}}{{--يوجد تدريب عملي بنفس الرمز الذي قمت بادخاله--}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="selectbasic">{{__('translate.Course Type')}}{{-- نوع التدريب العملي --}}<span style="color: red">*</span></label>
                                            <div class="col-lg-12">
                                            <select id="c_course_type" name="c_course_type" class="form-control btn-square">
                                                <option selected="" disabled="" value="">--{{__('translate.Choose')}}{{-- اختيار --}}--</option>
                                                <option value="0">{{__('translate.Theoretical')}} {{-- نظري --}}</option>
                                                <option value="1">{{__('translate.Practical')}} {{-- عملي --}}</option>
                                                <option value="2">{{__('translate.Theoretical - Practical')}} {{-- نظري - عملي --}}</option>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="selectbasic">{{__('translate.Course Hours')}}{{-- ساعات التدريب العملي --}}<span style="color: red">*</span></label>
                                            <div class="col-lg-12">
                                            <select id="c_hours" name="c_hours" class="form-control btn-square">
                                                <option selected="" disabled="" value="">--{{__('translate.Choose')}}{{-- اختيار --}}--</option>
                                                <option value="0">0</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Course Reference Code')}}{{-- الرمز المرجعي للتدريب العملي --}}<span style="color: red">*</span></label>
                                            <div class="input-container">
                                                <i id="ref_ok_icon" class="icon fa fa-check" style="color:#ef681a" hidden></i>
                                                <i id="ref_search_icon" class="icon_spinner fa fa-spin fa-refresh" hidden></i>
                                                <input id="c_reference_code" name="c_reference_code" type="text"
                                                    class="form-control btn-square input-md" oninput="validateEngNumInput(this)" onkeyup="checkCourseCode(this.value,'refCode')">
                                            </div>

                                            <div id="similarCourseCodeRefMessage" style="color:#dc3545" hidden>
                                                <span>{{__('translate.There is course with the same reference code entered')}}{{--يوجد تدريب عملي بنفس الرمز المرجعي الذي قمت بادخاله--}}</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Course Description')}}{{-- وصف التدريب العملي --}}<span style="color: red">*</span></label>
                                            <div class="col-lg-12">
                                                <textarea id="c_description" name="c_description" type="text"
                                                    class="form-control btn-square input-md" rows="6"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                        </div>

                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary" id="add_course">{{__('translate.Add Course')}}{{-- إضافة تدريب عملي --}}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
