<div class="modal fade show" id="AddBranchModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row p-3 m-5">

                    <div class="col-md-6 text-center">


                        <h1><span class="fa fa-plus" style="text-align: center; font-size:80px; "></span></h1>


                        <h1>{{ __('translate.Add Branch') }}{{-- إضافة فرع --}}</h1>

                        <hr>
                        <p>{{ __('translate.In this section, you can add a new branch') }}{{-- في هذا القسم يمكنك إضافة فرع جديد --}}</p>


                    </div>

                    <div class="col-md-6">
                        <form class="form-horizontal" id="addBranchForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">



                                    <!-- Text input-->
                                    <div class="mb-3 row">
                                        <label class="col-lg-12 form-label "
                                            for="textinput">{{ __('translate.Phone 1') }}{{-- هاتف 1 --}}</label>
                                        <div class="col-lg-12">
                                            <input id="phone1" type="text" name="phone1" tabindex="1"
                                                class="form-control btn-square input-md" autofocus>

                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="mb-3 row">
                                        <label class="col-lg-12 form-label "
                                            for="textinput">{{ __('translate.Phone 2') }}{{-- هاتف 2 --}}</label>
                                        <div class="col-lg-12">
                                            <input tabindex="3" id="phone2" name="phone2"
                                                class="form-control btn-square input-md">

                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="mb-3 row">
                                        <label class="col-lg-12 form-label "
                                            for="textinput">{{ __('translate.Branch Address') }}{{-- عنوان الفرع --}}</label>
                                        <div class="col-lg-12">
                                            <input tabindex="4" id="address_addB" type="text" name="address_addB"
                                                class="form-control btn-square input-md">

                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-lg-12 form-label">
                                            <div class="col-lg-12" id="departments_group1">
                                                <label
                                                    for="departments1">{{ __('translate.Branch Departments') }}{{-- أقسام الفرع --}}</label>
                                                <select tabindex="5" class="js-example-basic-single col-sm-12"
                                                    name="departments1" id="departments1" multiple="multiple" multiple>

                                                    @foreach ($companyDepartments as $key1)
                                                        <option value="{{ $key1->d_id }}" >{{ $key1->d_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <input hidden id="c_id" name="c_id" value="{{ $company->c_id }}">
                                    <input hidden id="manager_id" name="manager_id"
                                        value="{{ $company->c_manager_id }}">
                                    <input hidden id="departmentsList" name="departmentsList">
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer ">
                <button type="submit"
                    class="btn btn-primary">{{ __('translate.Add Branch') }}{{-- إضافة فرع --}}</button>
                <button type="button" class="btn btn-light"
                    data-bs-dismiss="modal">{{ __('translate.Cancel') }}{{-- إلغاء --}}</button>
            </div>
            </form>
        </div>
    </div>

</div>
