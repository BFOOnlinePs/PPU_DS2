<div class="modal fade show" id="AddCoursesStudentModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" >
                            <h1><span class="fa fa-book" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__('translate.Enroll in a Course')}} {{-- تسجيل التدريب العملي --}}</h3>
                            <hr>
                            <p >{{__('translate.In this section, you can enroll a student in a course')}} {{-- في هذا القسم يمكن تسجيل تدريب عملي للطالب --}}</p>
                        </div>
                        <div class="col-md-8">
                            <form class="form-horizontal" id="addCoursesStudentForm" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Course')}} {{-- تدريب عملي --}}</label>
                                            <select autofocus class="js-example-basic-single col-sm-12" name="c_id" id="select-course">
                                                @foreach ($courses as $course)
                                                    <option value="{{$course->c_id}}">{{$course->c_name}} | {{$course->c_course_code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="">مسؤول المتابعة</label>
                                            <select required class="js-example-basic-single col-sm-12" name="supervisor_id" id="supervisor_id">
                                                <option value="">اختر مسؤول للمتابعة</option>
                                                @foreach ($supervisors as $supervisor)
                                                    <option value="{{$supervisor->u_id}}">{{$supervisor->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary" id="button_add_course_in_modal" onclick="add_course()">{{__('translate.Enroll in a Course')}} {{-- تسجيل تدريب عملي --}}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}} {{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
