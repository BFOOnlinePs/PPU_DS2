<div class="modal fade show" id="ShowCourseModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">

                        <div class="col-md-4 text-center" style="margin: auto">


                                <h1><span class="fa fa-list" style="text-align: center; font-size:80px; "></span></h1>


                                <h1>{{__('translate.Display Course')}}{{-- استعراض التدريب العملي --}}</h1>

                                <hr>
                                <p>{{__('translate.In this section, you can display course data')}} {{-- في هذا القسم يمكنك استعراض البيانات الخاصة بالتدريبات العملية --}}</p>


                        </div>

                        <div class="col-md-8">

                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Text input-->
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Course Name')}} {{-- اسم التدريب العملي --}}</label>
                                            <div class="col-lg-12">
                                                <input id="show_c_name" name="c_name" disabled type="text" class="form-control btn-square input-md">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">


                                        <!-- Text input-->
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Course Code')}}{{-- رمز التدريب العملي --}}</label>
                                            <div class="col-lg-12">
                                                <input id="show_c_course_code" name="c_course_code" disabled type="text" class="form-control btn-square input-md">

                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Course Hours')}}{{-- ساعات التدريب العملي --}}</label>
                                            <div class="col-lg-12">
                                                <input id="show_c_hours" name="c_hours" disabled type="text" class="form-control btn-square input-md">

                                            </div>
                                        </div>






                                    </div>
                                    <div class="col-md-6">

                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="selectbasic">{{__('translate.Course Type')}}{{-- نوع التدريب العملي --}}</label>
                                            <div class="col-lg-12">
                                            <select id="show_c_course_type" name="c_course_type" disabled class="form-control btn-square">
                                                <option value="-1">اختيار</option>
                                                <option value="0">{{__('translate.Theoretical')}} {{-- نظري --}}</option>
                                                <option value="1">{{__('translate.Practical')}} {{-- عملي --}}</option>
                                                <option value="2">{{__('translate.Theoretical - Practical')}} {{-- نظري - عملي --}}</option>
                                            </select>
                                            </div>
                                        </div>

                                        {{-- <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">نوع التدريب العملي</label>
                                            <div class="col-lg-12">
                                                <input id="show_c_course_type" name="c_course_type" disabled type="text" class="form-control btn-square input-md">

                                            </div>
                                        </div> --}}



                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Course Reference Code')}}{{-- الرمز المرجعي للتدريب العملي --}}</label>
                                            <div class="col-lg-12">
                                                <input id="show_c_reference_code" name="c_reference_code" disabled type="text" class="form-control btn-square input-md">

                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Course Description')}}{{-- وصف التدريب العملي --}}</label>
                                            <div class="col-lg-12">
                                                <textarea id="show_c_description" name="c_description" disabled type="text"
                                                class="form-control btn-square input-md" rows="6">
                                                </textarea>

                                            </div>
                                        </div>
                                    </div>

                                </div>


                        </div>

                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Close')}}{{-- إغلاق --}}</button>
                </div>

        </div>
    </div>
</div>
