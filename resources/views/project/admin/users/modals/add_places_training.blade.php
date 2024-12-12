<div class="modal fade show" id="AddPlacesTrainingModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" onclick="clear_modal_add()"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" >
                            <h1><span class="fa fa-clipboard" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__('translate.Enroll student in training')}} {{-- تسجيل الطالب في تدريب --}}</h3>
                            <hr>
                            <p>{{__('translate.In this section, you can enroll the student in a training')}} {{-- في هذا القسم يمكنك تسجيل الطالب في تدريب --}}</p>
                        </div>
                        <div class="col-md-8">
                            <form class="form-horizontal" id="addPlacesTrainingForm" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Company')}} {{--  الشركة --}}</label>
                                            <select autofocus class="js-example-basic-single col-sm-12" id="select-companies" onchange="change_company(this.value)" name="company" required>
                                                <option value=""></option>
                                                @foreach ($companies as $company)
                                                    <option value="{{$company->c_id}}">{{$company->c_name}}</option>
                                                @endforeach
                                            </select>
                                            <a href="{{route('admin.companies.index')}}">{{__('translate.Click to Add a New Company')}} {{-- انقر لإضافة شركة جديدة --}}</a>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Branch')}} {{-- الفرع --}}</label>
                                            <select autofocus class="js-example-basic-single col-sm-12" id="select-branches" name="branch" onchange="change_branch(this.value)" disabled>
                                                @if ($branches != null)
                                                    <option value=""></option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{$branch->b_id}}">{{$branch->b_address}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="approval_file">{{__('translate.Approval File')}} {{-- ملف الموافقة --}}</label>
                                            <input type="file" id="approval_file" name="file">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 row">
                                            <label for="textinput">{{__('translate.Trainer (from the company)')}} {{-- المدرب من الشركة --}}</label>
                                            <select autofocus class="js-example-basic-single col-sm-12" id="select-trainers" name="trainer" disabled>
                                                    @if ($trainers != null)
                                                    <option value=""></option>
                                                    @foreach ($trainers as $trainer)
                                                        <option value="{{$trainer->ce_id}}">{{$trainer->ce_id}}</option>
                                                    @endforeach
                                                    @endif
                                            </select>
                                        </div>
                                        <br>
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Department Associated with this Branch')}} {{-- القسم التابع للفرع --}}</label>
                                            <select autofocus class="js-example-basic-single col-sm-12" id="select-departments" name="department" disabled>
                                                @if ($departments != null)
                                                    <option value=""></option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{$department->d_id}}">{{$department->d_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="textinput">{{__('translate.Course')}} {{-- تدريب عملي --}}</label>
                                            <select required autofocus class="js-example-basic-single col-sm-12" id="select-course" name="course">
                                                @if ($registrations != null)
                                                    <option value=""></option>
                                                    @foreach ($registrations as $registration)
                                                        <option value="{{$registration->r_id}}">{{$registration->courses->c_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="error-container">

                                </div>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary">{{__('translate.Enroll student in training')}} {{-- تسجيل الطالب في تدريب --}}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" onclick="clear_modal_add()">{{__('translate.Cancel')}} {{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

