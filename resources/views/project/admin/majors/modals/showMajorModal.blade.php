<div class="modal fade show" id="showMajorModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border: none;">
                    <div class="modal-header" style="height: 73px;">
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row p-3 m-5">

                                <div class="col-md-4 text-center" style="margin: auto">


                                        <h1><span class="fa fa-list" style="text-align: center; font-size:80px; "></span></h1>


                                        <h3>{{__('translate.Display Major')}}{{-- استعراض التخصص --}}</h3>

                                        <hr>
                                        <p>{{__('translate.In this section, you can display major information')}}{{-- في هذا القسم، يمكنك مراجعة بيانات التخصص  --}}</p>


                                </div>

                                <div class="col-md-8">

                                    <div class="row">
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__("translate.Major Name")}} {{-- اسم التخصص --}}</label>
                                            <div class="col-lg-12">
                                                <input id="show_m_name" name="m_name" disabled type="text" class="form-control btn-square input-md">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Major Reference Code')}} {{-- الرمز المرجعي للتخصص --}}</label>
                                            <div class="col-lg-12">
                                                <input id="show_m_reference_code" name="m_reference_code" disabled type="text" class="form-control btn-square input-md">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 row">
                                            <label class="col-lg-12 form-label " for="textinput">{{__('translate.Major Description')}}{{-- وصف التخصص --}}</label>
                                            <div class="col-lg-12">
                                                <textarea id="show_m_description" name="m_description"
                                                disabled type="text" class="form-control btn-square input-md" rows="6"></textarea>

                                            </div>
                                        </div>
                                    </div>

                                    <input id="show_m_id" name="m_id" hidden type="text" class="form-control btn-square input-md">

                                </div>

                            </div>
                        </div>
                        <div class="modal-footer ">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Close')}}{{-- إغلاق --}}</button>
                        </div>

                </div>
            </div>
        </div>
