<div class="modal fade show" id="EditPlacesTrainingModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-content" style="border: none;">
                <form class="form-horizontal" id="editPlacesTrainingForm" enctype="multipart/form-data">
                <div class="modal-header" style="height: 73px;">
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row p-3 m-5">
                            <div class="col-md-4 text-center" >
                                <h1><span class="fa fa-clipboard" style="text-align: center; font-size:80px; "></span></h1>
                                <h3>{{__("translate.Edit Student's Training Information")}}{{--تعديل معلومات تدريب الطالب--}}</h3>
                                <hr>
                                <p>{{__("translate.In this section, you can edit student's training information")}}{{--في هذا القسم يمكنك تعديل معلومات تدريب الطالب--}}</p>
                            </div>
                            <div class="col-md-8">
                                    @csrf
                                    @method('post')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3 row">
                                                <label for="">{{__('translate.Company')}} {{--  الشركة --}}</label>
                                                <select autofocus class="js-example-basic-single col-sm-12" id="c_name" name="c_name" disabled>
                                                </select>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="">{{__('translate.Branch')}} {{-- الفرع --}}</label>
                                                <select required autofocus class="js-example-basic-single col-sm-12" id="selectEditBranch" name="branch" onchange="branch_change_editing(this.value)">
                                                </select>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="">{{__('translate.Training Status')}}{{--حالة التدريب--}}</label>
                                                <select autofocus class="js-example-basic-single col-sm-12" id="select_status" name="select_status">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 row">
                                                <label for="">{{__('translate.Trainer (from the company)')}} {{-- المدرب من الشركة --}}</label>
                                                <select autofocus class="js-example-basic-single col-sm-12" id="selectEditTrainer" name="Trainer">
                                                </select>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="">{{__('translate.Department Associated with this Branch')}} {{-- القسم التابع للفرع --}}</label>
                                                <select autofocus class="js-example-basic-single col-sm-12" id="selectEditDepartment" name="department"  disabled>
                                                </select>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="">{{__('translate.Course')}} {{-- المساق --}}</label>
                                                <select autofocus class="js-example-basic-single col-sm-12" id="selectEditCourse" name="course">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <input type="hidden" id="sc_id">
                        <button type="button" class="btn btn-primary" onclick="update_places_training()">{{__("translate.Edit Student's Training Information")}}{{--تعديل معلومات تدريب الطالب--}}</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}} {{-- إلغاء --}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

