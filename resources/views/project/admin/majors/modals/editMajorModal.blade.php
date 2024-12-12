<div class="modal fade show" id="editMajorModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row p-3 m-5">

                    <div class="col-md-4 text-center" style="margin: auto">


                        <h1><span class="fa fa-edit" style="text-align: center; font-size:80px; "></span></h1>


                        <h3>{{ __('translate.Edit Major') }}{{-- تعديل التخصص --}}</h3>

                        <hr>
                        <p>{{ __('translate.In this section, you can edit the major') }}{{-- في هذا القسم يمكنك تعديل التخصص المراد --}}</p>


                    </div>

                    <div class="col-md-8">
                        <form id="editMajorForm" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="mb-3 row">
                                    <label class="col-lg-12 form-label "
                                        for="textinput">{{ __('translate.Major Name') }} {{-- اسم التخصص --}}</label>
                                    <div class="col-lg-12">
                                        <input required id="edit_m_name" type="text"
                                            class="form-control btn-square input-md" name="m_name" autofocus
                                            oninput="validateInput(this)">

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- <div class="mb-3 row">
                                                <label class="col-lg-12 form-label " for="textinput">الرمز المرجعي للتخصص</label>
                                                <div class="col-lg-12">
                                                  <input id="edit_m_reference_code" type="text" class="form-control btn-square input-md"
                                                    name="m_reference_code" oninput="validateEngNumInput(this)">
                                                </div>
                                            </div> --}}

                                <div class="mb-3 row">
                                    <label class="col-lg-12 form-label "
                                        for="textinput">{{ __('translate.Major Reference Code') }}
                                        {{-- الرمز المرجعي للتخصص --}}</label>
                                    <div class="input-container">
                                        <i id="edit_ok_icon" class="icon fa fa-check" style="color:#ef681a" hidden></i>
                                        <i id="edit_search_icon" class="icon_spinner fa fa-spin fa-refresh" hidden></i>
                                        <input required class="form-control" type="text" id="edit_m_reference_code"
                                            name="m_reference_code" onkeyup="checkMajorCode(this.value,'edit')"
                                            oninput="validateEngNumInput(this)">
                                    </div>

                                    <div id="edit_similarMajorCodeMessage" style="color:#dc3545" hidden>
                                        <span>{{ __('translate.There is major with the same code entered') }}{{-- يوجد تخصص بنفس الرمز الذي قمت بادخاله --}}</span>
                                    </div>
                                </div>



                            </div>

                            <div class="row">
                                <div class="mb-3 row">
                                    <label class="col-lg-12 form-label "
                                        for="textinput">{{ __('translate.Major Description') }}{{-- وصف التخصص --}}</label>
                                    <div class="col-lg-12">
                                        <textarea required id="edit_m_description" type="text" class="form-control btn-square input-md" name="m_description"
                                            rows="6"></textarea>

                                    </div>
                                </div>
                            </div>

                            <input id="edit_m_id" name="m_id" hidden type="text"
                                class="form-control btn-square input-md">
                            <input id="oldRefCode" name="oldRefCode" hidden type="text"
                                class="form-control btn-square input-md">
                    </div>

                </div>
            </div>
            <div class="modal-footer ">
                <button type="submit" class="btn btn-primary"
                    id="edit_major">{{ __('translate.Edit Major') }}{{-- تعديل التخصص --}}</button>
                <button type="button" class="btn btn-light"
                    data-bs-dismiss="modal">{{ __('translate.Cancel') }}{{-- إلغاء --}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
