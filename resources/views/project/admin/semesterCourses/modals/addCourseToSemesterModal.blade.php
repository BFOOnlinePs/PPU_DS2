<div class="modal fade show" id="AddCourseToSemesterModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">

                        <div class="col-md-4 text-center" >


                                <h1><span class="fa fa-plus" style="text-align: center; font-size:80px; "></span></h1>


                                <h3>{{__('translate.Add Course to Current Semester')}}{{-- إضافة تدريب عملي إلى الفصل الحالي --}}</h3>

                                <hr>
                                <p>{{__('translate.In this section, you can add one or more courses to the current semester')}}{{-- في هذا القسم يمكنك إضافة تدريب عملي أو عدة تدريبات عملية إلى الفصل الحالي --}}</p>


                        </div>


                            <div class="col-md-8">
                                <form class="form-horizontal" id="addCourseToSemesterForm" action="" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <div class="mb-2">
                                    <label class="col-form-label">{{__('translate.Courses (You can choose one or more courses)')}}{{-- (التدريبات العملية (بإمكانك اختيار تدريب عملي أو عدة تدريبات عملية --}}</label>
                                    <select class="js-example-basic-single col-sm-12" multiple="multiple" id="selectedCourses" multiple>
                                        @foreach($course as $key)
                                        <option value="{{$key->c_id}}">{{$key->c_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <select class="js-example-basic-single col-sm-12">
                                    @foreach($course as $key)
                                        <option value="{{$key->c_id}}">{{$key->c_name}}</option>
                                    @endforeach
                                </select> --}}


                            </div>

                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary">{{__('translate.Add')}}{{-- إضافة --}}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
