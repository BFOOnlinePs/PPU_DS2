@extends('layouts.app')
@section('title')
{{__('translate.Add Company')}}{{--إضافة شركة--}}
@endsection
@section('header_title')
     {{__('translate.Companies')}}{{-- الشركات --}}
@endsection
@section('header_title_link')
{{__('translate.Companies Management')}}{{--إدارة الشركات--}}
@endsection
@section('header_link')
{{__('translate.Add Company')}}{{--إضافة شركة--}}
@endsection
@section('style')

<style>
.loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.35); /* خلفية شفافة لشاشة التحميل */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999; /* يجعل شاشة التحميل فوق جميع العناصر الأخرى */
}

.f1-steps {
    display: flex;
    justify-content: space-around; /* Distribute the steps evenly along the horizontal axis */
    align-items: center; /* Center the steps vertically */
}

.f1-step {
    text-align: center;
    flex: 1; /* Allow each step to grow and fill the available space */
}

/* Add some basic styling to position the icon */
.input-container {
      position: relative;
      /* width: 300px; Set the width of the input container */
    }

    /* Style the input to provide some spacing for the icon */
    input {
      padding-left: 30px; /* Add padding to the left of the input to make room for the icon */
      width: 100%; /* Make the input take up the full width of the container */
    }
</style>

@endsection
@section('content')

<div class="card" style="padding-left:0px; padding-right:0px;">

    {{-- <div class="card-header pb-0">
        <h1>إضافة شركة</h1>
    </div> --}}
    <div class="card-body" >

        <!--loading whole page-->
        <div class="loader-container loader-box" id="loaderContainer" hidden>
            <div class="loader-3"></div>
        </div>
        <!--//////////////////-->
        <div>

        </div>
        <form class="f1" method="post" id="companyForm">
            <div class="f1-steps">
                <div class="f1-progress">
                    <div class="f1-progress-line"></div>
                </div>
                <div class="f1-step active">
                    <div class="f1-step-icon"><i class="fa fa-user"></i></div>
                    <p>{{__('translate.User')}}{{--المستخدم--}}</p>
                </div>
                <div class="f1-step">
                    <div class="f1-step-icon"><i class="fa fa-file-text-o"></i></div>
                    <p>{{__('translate.Company Information')}}{{-- معلومات الشركة --}}</p>
                </div>
                <div class="f1-step">
                    <div class="f1-step-icon"><i class="fa fa-th-large"></i></div>
                    <p>{{__('translate.Company Departments')}}{{-- أقسام الشركة --}}</p>
                </div>
                <div class="f1-step">
                    <div class="f1-step-icon"><i class="fa fa-sitemap"></i></div>
                    <p>{{__('translate.Company Branches')}}{{-- فروع الشركة --}}</p>
                </div>
                <div class="f1-step">
                    <div class="f1-step-icon"><i class="fa fa-file-text"></i></div>
                    <p>{{__('translate.Summary')}}{{--الملخص--}}</p>
                </div>
            </div>

            <fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 form-group">
                            <label for="f1-first-name">{{__("translate.Company Name")}}{{--اسم الشركة--}} <span style="color: red">*</span></label>

                            <div class="input-container">
                                <i id="ok_icon" class="icon fa fa-check" style="color:#ef681a" hidden></i>
                                <i id="search_icon" class="icon_spinner fa fa-spin fa-refresh" hidden></i>
                                <input class="form-control" type="text" id="c_name" name="c_name" required="" onkeyup="checkCompany(this.value)">
                            </div>

                            <div id="similarCompanyMessage" style="color:#dc3545" hidden>
                                <span>{{__("translate.There is company with the same name you entered,")}}{{--يوجد شركة بنفس الاسم الذي قمت بادخاله،--}}</span>
                                <u><a id="companyLink" style="color:#dc3545">{{__("translate.To move to the edit click here")}}{{--للانتقال إلى التعديل قم بالضغط هنا--}}</a></u>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.company_english_name')}} <span style="color: red">*</span></label>
                            <input class="f1-last-name form-control" id="c_english_name" type="text" name="c_english_name" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Owner')}}{{-- الشخص المسؤول --}} <span style="color: red">*</span></label>
                            <input class="f1-last-name form-control" id="name" type="text" name="name" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="f1-first-name"> {{__('translate.Email')}} {{-- البريد الإلكتروني --}} <span style="color: red">*</span></label>
                            <div class="input-container">
                                <i id="email_ok_icon" class="icon fa fa-check" style="color:#ef681a" hidden></i>
                                <i id="email_search_icon" class="icon_spinner fa fa-spin fa-refresh" hidden></i>
                                <input class="form-control" id="email" type="text" name="email" required="" oninput="validateEmail(this)">
                            </div>

                            <div id="similarEmailMessage" style="color:#dc3545" hidden>
                                <span>{{__("translate.Email has already been used")}}{{--البريد الإلكتروني موجود بالفعل--}}</span>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Password')}} {{-- كلمة المرور --}} <span style="color: red">*</span></label>
                            <input class="f1-password form-control" id="password" type="password" name="password" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="f1-last-name">{{__('translate.Company Phone Number')}}{{-- رقم هاتف الشركة --}} <span style="color: red">*</span></label>
                            <input class="f1-last-name form-control" id="phoneNum" type="text" name="phoneNum" required="" oninput="validateInput(this)">
                            <div id="errorMessage_phoneNum" style="color:#dc3545" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="f1-last-name">{{__('translate.Company Address')}}{{-- عنوان الشركة --}} <span style="color: red">*</span></label>
                            <input class="f1-last-name form-control" id="address" type="text" name="address" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="f1-last-name">المدينة <span style="color: red">*</span></label>
                            <select class="form-control select2bs4" name="b_city_id" id="">
                                @foreach($cities as $key)
                                    <option value="{{ $key->id }}">{{ $key->city_name_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Number of Company Branches - including the main branch')}}{{--عدد فروع الشركة - "يشمل الفرع الرئيسي"--}} <span style="color: red">*</span></label>
                            <select id="branchesNum" name="branchesNum" class="form-control btn-square">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">

                </div>

                <div class="row">

                </div>

                <div class="row">

                </div>



                <div class="f1-buttons">
                    <button class="btn btn-primary" id="firstButton" onclick="firstStep()" type="button">{{__('translate.Next')}}{{--التالي--}}</button>
                    <button class="btn btn-primary btn-next" id="firstStepButton" type="button" hidden></button>
                    {{-- <button class="btn btn-primary btn-next" id="firstStepButton" type="button">test</button> --}}
                </div>
            </fieldset>



            <fieldset>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Company Name')}}{{--اسم الشركة--}}</label>

                            {{-- <input class="f1-last-name form-control" id="companyName" type="text" name="companyName" disabled> --}}
                            <input class="f1-last-name form-control" id="companyName" name="companyName" disabled>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Website')}}{{-- الموقع الإلكتروني --}}</label>

                            <input class="form-control" id="c_website" name="c_website" oninput="validateArabicText(this)">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Company Type')}}{{-- نوع الشركة --}} <span style="color: red">*</span></label>
                            <select id="c_type" name="c_type" class="form-control btn-square">
                                <option selected="" disabled="" value="">--{{__('translate.Choose')}}--{{--اختيار--}}</option>
                                <option value="1">{{__('translate.Public Sector')}}{{-- قطاع عام --}}</option>
                                <option value="2">{{__('translate.Private Sector')}}{{-- قطاع خاص --}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Company Category')}}{{--تصنيف الشركة--}} <span style="color: red">*</span></label>
                            <select id="c_category" name="c_category" class="form-control btn-square">
                                <option selected="" disabled="" value="">--{{__('translate.Choose')}}--{{--اختيار--}}</option>
                                @foreach($categories as $key)
                                    <option value="{{$key->cc_id}}">{{$key->cc_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Company Description')}}{{-- وصف الشركة --}}</label>
                            <textarea  class="form-control" id="c_description" name="c_description" rows="6"></textarea>
                        </div>
                    </div>
                </div>


                <input hidden id="manager_id" name="manager_id">
                {{-- <input hidden id="company_id" name="company_id"> --}}

                <div class="f1-buttons">
                    {{-- <button class="btn btn-primary btn-previous" type="button">رجوع</button> --}}
                    <button class="btn btn-primary" type="button" onclick="secondStep()">{{__('translate.Next')}}{{--التالي--}}</button>
                    <button class="btn btn-primary btn-next" id="secondStepButton" type="button" hidden></button>
                    {{-- <button class="btn btn-primary btn-next" id="firstStepButton" type="button">test</button> --}}
                </div>
            </fieldset>

            <fieldset>
                <div class="row p-3 m-5 mt-3">

                        <div class="col-md-4 text-center">


                                <h1><span class="fa fa-th" style="text-align: center; font-size:80px; "></span></h1>


                                <h3>{{__('translate.Add Department to the Company')}}{{-- إضافة قسم إلى الشركة --}}</h3>

                                <hr>
                                <p>{{__('translate.In this section, you can add departments to the current company')}}{{--في هذا القسم يمكنك إضافة الأقسام الخاصة بالشركة الحالية--}}</p>


                        </div>


                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        {{-- <label for="f1-first-name">{{__('translate.Department Name')}}اسم القسم</label> --}}
                                        <input class="form-control" id="d_name" name="d_name" placeholder="{{__('translate.Department Name')}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-info" type="button" onclick="addDepartment()">{{__('translate.Add')}}{{-- إضافة  --}}</button>
                                </div>
                            </div>
                            {{-- <div class="row" id="departmentsArea">

                                <div class="col-md-6 mb-1 mr-1" style="background-color: #ef681a4e; border-radius:10px">
                                    <h6>test</h6>
                                </div>


                            </div> --}}
                            <div class="row" >
                                <div class="col-md-8" id="departmentsArea">



                                </div>
                            </div>
                        </div>

                </div>


                <input hidden id="departmentsList" name="departmentsList">

                <div class="f1-buttons">
                    <button class="btn btn-primary" onclick="departmentStep()" type="button">{{__('translate.Next')}}{{--التالي--}}</button>
                    <button class="btn btn-primary btn-next" id="departmentStepButton" type="button" hidden></button>
                </div>
            </fieldset>

            <fieldset>

                <div class="row">
                    <div class="col-md-6">
                        <div class="ribbon-wrapper card shadow-sm" style="border-radius: 5px;">
                          <div class="card-body">
                            <div class="ribbon ribbon-primary ribbon-right">{{__('translate.Main Branch')}}{{-- الفرع --}}</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone1_1">{{__('translate.Phone 1')}}{{-- هاتف 1 --}}</label>
                                        <input class="f1-last-name form-control" id="phone1_1" type="text" name="phone1_1" disabled required="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone2_1">{{__('translate.Phone 2')}}{{-- هاتف 2 --}}</label>
                                        <input class="f1-last-name form-control" id="phone2_1"  name="phone2_1" required="" oninput="validateInput(this)">
                                        <div id="errorMessage_phone2_1" style="color:#dc3545" class="error-message"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="address1">{{__('translate.Branch Address')}}{{-- عنوان الفرع --}}</label>
                                        <input class="f1-last-name form-control" id="address1" type="text" disabled name="address1" required="">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="departments_group1" hidden>
                                        <label for="departments1">{{__('translate.Branch Departments')}}{{-- أقسام الفرع --}} <span style="color: red">*</span></label>
                                        <select class="js-example-basic-single col-sm-12" multiple="multiple" type="text" id="departments1" multiple required></select>
                                    </div>
                                </div>

                                <input hidden id="department_for_1" name="department_for_1">

                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="secondBranch" hidden>
                        <div class="ribbon-wrapper card shadow-sm" style="border-radius: 5px;">
                            <div class="card-body">
                                <div class="ribbon ribbon-primary ribbon-right">{{__('translate.Branch')}} {{-- الفرع --}} 2</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone1_2">{{__('translate.Phone 1')}}{{-- هاتف 1 --}} <span style="color: red">*</span></label>
                                            <input class="f1-last-name form-control" id="phone1_2" type="text" name="phone1_2" required="" oninput="validateInput(this)">
                                            <div id="errorMessage_phone1_2" style="color:#dc3545" class="error-message"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone2_2">{{__('translate.Phone 2')}}{{-- هاتف 2 --}}</label>
                                            <input class="f1-last-name form-control" id="phone2_2" name="phone2_2" oninput="validateInput(this)">
                                            <div id="errorMessage_phone2_2" style="color:#dc3545" class="error-message"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address2">{{__('translate.Branch Address')}}{{-- عنوان الفرع --}} <span style="color: red">*</span></label>
                                            <input class="f1-last-name form-control" id="address2" type="text" name="address2" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="departments_group2" hidden>
                                            <label for="departments2">{{__('translate.Branch Departments')}}{{-- أقسام الفرع --}} <span style="color: red">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" multiple="multiple" id="departments2" multiple></select>
                                        </div>
                                    </div>

                                    <input hidden id="department_for_2" name="department_for_2">
                                </div>
                              </div>
                        </div>
                    </div>

                    <input hidden id="company_id" name="company_id">

                </div>




                <div id="branches">

                </div>




                <div class="f1-buttons">
                    {{-- <button class="btn btn-primary btn-previous" type="button">رجوع</button> --}}
                    {{-- <button class="btn btn-primary btn-submit" type="submit">إضافة</button> --}}
                    <button class="btn btn-primary" id="thirdButton" type="button" onclick="thirdStep()">{{__('translate.Next')}}{{--التالي--}}</button>
                    <button class="btn btn-primary btn-next" id="thirdStepButton" type="button" hidden></button>
                </div>
            </fieldset>

            <fieldset>

                <h1 class="mt-3" id="company_name_summary"></h1>
                <br>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th style="background-color: #ecf0ef82;" colspan="2">{{__('translate.Main Information')}}{{--المعلومات الأساسية--}}</th>
                        </tr>
                      <tbody>
                        <tr>
                          <td class="col-md-3">{{__('translate.Owner')}}{{-- الشخص المسؤول --}}</td>
                          <td id="manager_summary"></td>
                        </tr>
                        <tr>
                          <td class="col-md-3">{{__('translate.Email')}} {{-- البريد الإلكتروني --}}</td>
                          <td id="email_sammury"></td>
                        </tr>
                        <tr>
                          <td class="col-md-3">{{__('translate.Company Phone Number')}}{{-- رقم هاتف الشركة --}}</td>
                          <td id="phone_summary"></td>
                        </tr>
                        <tr id="phone2_summary_area" hidden>
                          <td class="col-md-3">{{__('translate.Phone 2')}}{{-- هاتف 2 --}}</td>
                          <td id="phone2_summary"></td>
                        </tr>
                        <tr>
                          <td class="col-md-3">{{__('translate.Company Address')}}{{-- عنوان الشركة --}}</td>
                          <td id="address_summary"></td>
                        </tr>
                        <tr>
                          <td class="col-md-3">{{__('translate.Company Category')}}{{-- تصنيف الشركة --}}</td>
                          <td id="category_summary"></td>
                        </tr>
                        <tr>
                          <td class="col-md-3">{{__('translate.Company Type')}}{{-- نوع الشركة --}}</td>
                          <td id="type_summary"></td>
                        </tr>
                        <tr id="description_summary_area" hidden>
                          <td class="col-md-3">{{__('translate.Company Description')}}{{-- وصف الشركة --}}</td>
                          <td id="description_summary"></td>
                        </tr>
                        <tr id="main_branch_departments_area" hidden>
                          <td class="col-md-3">{{__('translate.Main Branch Departments')}}{{--أقسام الفرع الرئيسي--}}</td>
                          <td id="main_branch_departments"></td>
                        </tr>
                        <tr id="website_summary_area" hidden>
                          <td class="col-md-3">{{__('translate.Website')}}{{-- الموقع الإلكتروني --}}</td>
                          <td id="website_summary"></td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <br>

                <!--table for departments-->
                <div id="departments_table" hidden>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="background-color: #ecf0ef82;" colspan="2">{{__('translate.Company Departments')}}{{-- أقسام الشركة --}}</th>
                            </tr>
                        <tbody>
                            <tr>
                                <td id="departments_summary" class="col-md-3"></td>
                            </tr>

                        </tbody>
                        </table>
                    </div>
                </div>
                <!------------------------->
                <br>



                <div id="branches_summary">

                </div>



                <div class="f1-buttons">
                    {{-- <button class="btn btn-success" type="button">إنهاء</button> --}}
                    <a type="button" class="btn btn-success" href="{{ route('admin.companies.index') }}">{{__('translate.Finish')}}{{--إنهاء--}}</a>
                    <a type="button" class="btn btn-info" id="editCompanyLink">{{__('translate.Edit')}}{{-- تعديل --}}</a>
                    {{-- <button class="btn btn-info" type="button">تعديل</button> --}}
                </div>
            </fieldset>

        </form>


    </div>


    @include('project.admin.companies.modals.uncompletedCompanyModal')

    @include('layouts.loader')
</div>

@endsection


@section('script')
<script src="{{ asset('assets/js/form-wizard/form-wizard-three.js') }}"></script>
<script src="{{asset('assets/js/form-wizard/jquery.backstretch.min.js')}}"></script>

<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>



<script>
let companyForm = document.getElementById("companyForm");
let companyName;
let c_english_name;
let company_id;
let branchesNum = document.getElementById('branchesNum').value;
let branchesNumber = 1;
const departments = [];
let uncompletedCompanySize = 0;
let uncompletedCompany;
let continueValidation = true;

let language = document.documentElement.lang


function validateArabicText(inputElement) {
    var cleanedValue = inputElement.value.replace(/[\u0600-\u06FF]/g, '');
    if (!/[\u0600-\u06FF]/.test(cleanedValue)) {
        inputElement.value = cleanedValue;
    } else {
        inputElement.value = cleanedValue.slice(0, 5);
    }
}

//to check email format and check if it exists
function validateEmail(input) {
    document.getElementById('firstButton').disabled = true;
    let email = input.value.trim();

    if(email==""){
       input.style.borderColor = "";
       document.getElementById('firstButton').disabled = false;
       document.getElementById('email_ok_icon').hidden = true;
       document.getElementById('email_search_icon').hidden = true;
       document.getElementById('similarEmailMessage').hidden = true;
    }else{

        //to check if the string is arabic
        var cleanedValue = input.value.replace(/[^a-zA-Z0-9.,;:'"!@#$%^&*()_+{}\[\]:;<>,.?\/\\~`|\-=]+/g, '');
        if (!/[a-zA-Z0-9.,;:'"!@#$%^&*()_+{}\[\]:;<>,.?\/\\~`|\-=]/.test(cleanedValue)) {
            //if its arabic, it will clear it
            input.value = cleanedValue;
        }


        // this will validate the email format if its true
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Check if the email matches the regular expression
        if (!emailRegex.test(email)) {
            input.style.borderColor = "#dc3545"; // Invalid email, set border color to red
        }else{
            input.style.borderColor = "";
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })

            $.ajax({
                beforeSend: function(){
                    document.getElementById('email_search_icon').hidden = false;
                    document.getElementById('firstButton').disabled = true;
                    document.getElementById('similarEmailMessage').hidden = true;
                    document.getElementById('email_ok_icon').hidden = true;
                },
                url: "{{ route('users.add.check_email_not_duplicate') }}",
                method: "post",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'email': email
                },
                success: function(response) {
                    //continueValidation = true;
                    if(response.status == 'true'){
                        document.getElementById('firstButton').disabled = true;
                        document.getElementById('email_search_icon').hidden = true;
                        document.getElementById('email_ok_icon').hidden = true;
                        document.getElementById('similarEmailMessage').hidden = false;
                    }else{
                        document.getElementById('firstButton').disabled = false;
                        document.getElementById('similarEmailMessage').hidden = true;
                        document.getElementById('email_search_icon').hidden = true;
                        document.getElementById('email_ok_icon').hidden = false;
                    }
                },
                error: function(xhr, status, error) {
                    alert('error');
                }
            });
        }
    }

}


//for phone number - (accept just 10 digits and only numbers)
function validateInput(input) {

    inputID = input.id;

    // Remove non-numeric characters
    let sanitizedValue = input.value.replace(/\D/g, '');
    const errorMessageElement = document.getElementById(`errorMessage_${inputID}`);

    var wizardStep = input.closest('fieldset');
    //var stepButton = wizardStep.querySelector('button');

    //var parent_fieldset = $('.f1 #firstButton').parents('fieldset');
    // wizardStep.find('button').each(function() {
    //     console.log('hi')
    // })


    var buttonsInFieldset = wizardStep.querySelector('.btn').id;

    if(input.value==""){
        input.style.borderColor = "";
        errorMessageElement.textContent = '';
        document.getElementById(`${buttonsInFieldset}`).disabled = false;
    }else{



        // Limit to 10 digits
        sanitizedValue = sanitizedValue.slice(0, 10);

        // Update the input value
        input.value = sanitizedValue;

        if (sanitizedValue.length < 10) {
            // Invalid input
            //input.setCustomValidity('Please enter a 10-digit number.');
            //input.style.borderColor = "#dc3545";
            errorMessageElement.textContent = "{{__('translate.Please enter 10 digits number')}}";
            document.getElementById(`${buttonsInFieldset}`).disabled = true;
        } else {
            //input.style.borderColor = "";
            errorMessageElement.textContent = '';
            document.getElementById(`${buttonsInFieldset}`).disabled = false;
        }

    }




}

//for uncomleted companies
window.addEventListener("load", (event) => {

    var iconSpinners = document.querySelectorAll('.icon_spinner');
    var icons = document.querySelectorAll('.icon');


    $('#uncompletedCompanyModal').modal({backdrop: 'static', keyboard: false})

    iconSpinners.forEach((iconSpinner) => {

        iconSpinner.style.left = 'auto';
        iconSpinner.style.right = 'auto';
        iconSpinner.style.position = 'absolute';
        iconSpinner.style.top = '30%';
        iconSpinner.style.transform = 'translateY(-50%)';

        if(language=='ar'){
            iconSpinner.style.left = '10px';
        }else{
            iconSpinner.style.right = '10px';
        }

    });

    icons.forEach((icon) => {

        icon.style.left = 'auto';
        icon.style.right = 'auto';
        icon.style.position = "absolute";
        icon.style.top = "50%";
        icon.style.transform = "translateY(-50%)";

        if(language=='ar'){
            icon.style.left = '10px';
        }else{
            icon.style.right = '10px';
        }

    });


    uncompletedCompanySize = {{count($uncompletedCompany)}};
    if(uncompletedCompanySize != 0){

        uncompletedCompany = {!! json_encode($uncompletedCompany, JSON_HEX_APOS) !!};

        x=""
        for(i=0;i<uncompletedCompanySize;i++){
            x += `<div class="row mb-2">
                    <div class="col-md-6">
                        <h6>
                            ${uncompletedCompany[i].c_name}
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary" onclick="completeCompany(${i})" >{{__('translate.Complete')}}</button>
                    </div>
                  </div>`
        }

        $('#p_company').html(x);


        //show popup with companies and links to them
        // $('#uncompletedCompanyModal').modal('show');
    }

});

function completeCompany(index){

    document.getElementById('c_name').value = uncompletedCompany[index].c_name;
    document.getElementById('name').value = uncompletedCompany[index].manager.name;
    document.getElementById('email').value = uncompletedCompany[index].manager.email;
    document.getElementById('password').value = uncompletedCompany[index].manager.password;
    document.getElementById('phoneNum').value = uncompletedCompany[index].manager.u_phone1;
    document.getElementById('address').value = uncompletedCompany[index].manager.u_address;
    document.getElementById('phone1_1').value=uncompletedCompany[index].manager.u_phone1;
    document.getElementById('address1').value=uncompletedCompany[index].manager.u_address;
    document.getElementById('manager_id').value = uncompletedCompany[index].manager.u_id;
    document.getElementById('companyName').value = uncompletedCompany[index].c_name;
    document.getElementById('company_id').value = uncompletedCompany[index].c_id;
    document.getElementById('c_english_name').value = uncompletedCompany[index].c_english_name;

    // branchesNum = document.getElementById('branchesNum').value;
    // if(branchesNum<2){
    //     //cause adress2 and phone1 are required inputs in the wizard and it will not continue to the
    //     //next step even they are hidden, so when choose 1 branch they are actully exists but whithout values
    //     //so the wizard will not continue to next step
    //     //so this is the temp solution for this issue
    //     document.getElementById('address2').value = 0;
    //     document.getElementById('phone1_2').value = 0;
    // }

    $('#uncompletedCompanyModal').modal('hide');

}

//to check company name if it exists
function checkCompany(data){

    document.getElementById('ok_icon').hidden = true;

    if(data!=""){
        var csrfToken = $('meta[name="csrf-token"]').attr('content');


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })

        $.ajax({
            beforeSend: function(){
                document.getElementById('search_icon').hidden = false;
            },
            url: "{{ route('admin.companies.checkCompany') }}",
            method: "post",
            data: {
                'search': data,
                _token: '{!! csrf_token() !!}',
            },
            success: function(response) {

                if(response.data!=null){

                    company_id = response.company_id;

                    var editLink = "{{ route('admin.companies.edit', ['id' => 'company_id']) }}";
                    editLink = editLink.replace('company_id', company_id);
                    document.getElementById("companyLink").setAttribute("href",editLink);

                    document.getElementById('search_icon').hidden = true;
                    document.getElementById('ok_icon').hidden = true;

                    document.getElementById('similarCompanyMessage').hidden = false;

                }else{
                    document.getElementById('similarCompanyMessage').hidden = true;


                    document.getElementById('search_icon').hidden = true;
                    document.getElementById('ok_icon').hidden = false;
                }

            },
            error: function(xhr, status, error) {
                alert('error');
            }
        });
    }

}


function firstStep(){

    ///////////////////to check the inputs if empty in step 1 page///////////////////////
    var parent_fieldset = $('.f1 #firstButton').parents('fieldset');
    var if_next = true;

    parent_fieldset.find('input[type="text"], input[type="password"]').each(function() {

        if( $(this).val() == "" ) {
    		$(this).addClass('input-error');
            if_next = false;
    	}
    	else {
    		$(this).removeClass('input-error');
    	}

    });
    ////////////////////////////////////////////////////////////////////////////////////////

    //console.log(parent_fieldset)

    if(if_next){
        branchesNum = document.getElementById('branchesNum').value;
        if(branchesNum<2){
            //cause adress2 and phone1 are required inputs in the wizard and it will not continue to the
            //next step even they are hidden, so when choose 1 branch they are actully exists but whithout values
            //so the wizard will not continue to next step
            //so this is the temp solution for this issue
            document.getElementById('address2').value = 0;
            document.getElementById('phone1_2').value = 0;
        }

        branchesNumber = document.getElementById('branchesNum').value;

        ////////set phone number and address to the main branch in branches page///////
        phoneNum1 = document.getElementById('phoneNum').value;
        address1 = document.getElementById('address').value;

        document.getElementById('phone1_1').value=phoneNum1;
        document.getElementById('address1').value=address1;
        ///////////////////////////////////////////////////////////////////////////////

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
            data = $('#companyForm').serialize();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })

            $.ajax({
                beforeSend: function(){
                    //$('#LoadingModal').modal('show');
                    document.getElementById('loaderContainer').hidden = false;
                },
                type: 'POST',
                url: "{{ route('admin.companies.create') }}",
                data: data,
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                    console.log(response);
                    manager_id = response.manager_id;
                    document.getElementById('manager_id').value = manager_id;
                    document.getElementById('company_id').value = response.company_id;
                    companyName = document.getElementById("c_name").value;
                    document.getElementById('companyName').value = companyName;
                },
                complete: function(){
                    //$('#LoadingModal').modal('hide');
                    document.getElementById('loaderContainer').hidden = true;
                    document.querySelector('#firstStepButton').click();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        // if(uncompletedCompanySize!=0){
        //     document.querySelector('#firstStepButton').click();
        // }else{
        //     var csrfToken = $('meta[name="csrf-token"]').attr('content');
        //     data = $('#companyForm').serialize();

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': csrfToken
        //         }
        //     })

        //     $.ajax({
        //         beforeSend: function(){
        //             //$('#LoadingModal').modal('show');
        //             document.getElementById('loaderContainer').hidden = false;
        //         },
        //         type: 'POST',
        //         url: "{{ route('admin.companies.create') }}",
        //         data: data,
        //         dataType: 'json',
        //         success: function(response) {
        //             console.log('response');
        //             console.log(response);
        //             manager_id = response.manager_id;
        //             document.getElementById('manager_id').value = manager_id;
        //             document.getElementById('company_id').value = response.company_id;
        //             companyName = document.getElementById("c_name").value;
        //             document.getElementById('companyName').value = companyName;
        //         },
        //         complete: function(){
        //             //$('#LoadingModal').modal('hide');
        //             document.getElementById('loaderContainer').hidden = true;
        //             document.querySelector('#firstStepButton').click();
        //         },
        //         error: function(xhr, status, error) {
        //             console.error(xhr.responseText);
        //         }
        //     });
        // }

    }

    //document.getElementById('companyName').value = "companyName";
    //document.querySelector('#firstStepButton').click();

}

function secondStep(){
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    data = $('#companyForm').serialize();

    // if(document.getElementById('c_type').value==""){
    //     $('#c_type').addClass('input-error');
    //     if_submit = false;
    // }

    var parent_fieldset = $('.f1 #secondStepButton').parents('fieldset');
    var if_next = true;

    //console.log(parent_fieldset)

    parent_fieldset.find('select').each(function() {

        //console.log($(this)[0].id)
        //$('.select2-container--default .select2-selection--multiple').css('border-color', 'red')
        //if( $(this).value == "") {
        if(document.getElementById(`${$(this)[0].id}`).value=="") {
    		//$(this).css('border-color', 'red')
    		$(this).addClass('input-error');
            if_next = false;
    	}
    	else {
    		$(this).removeClass('input-error');
    	}

    });


    if(if_next){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })

        $.ajax({
            beforeSend: function(){
                //$('#LoadingModal').modal('show');
                document.getElementById('loaderContainer').hidden = false;
            },
            type: 'POST',
            url: "{{ route('admin.companies.updateCompany') }}",
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log("all has done")
            },
            complete: function(){
                //$('#LoadingModal').modal('hide');
                document.getElementById('loaderContainer').hidden = true;
                document.querySelector('#secondStepButton').click();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

    }


}

function thirdStep(){


    //to set selected values in department for each branch
    if(departments.length!=0){
        for(i=0;i<branchesNumber;i++){

            branchDepId=`department_for_${i+1}`;
            branchSelect = `#departments${i+1}`;

            //console.log("$(branchSelect).val()")
            //console.log($(branchSelect).val())
            depArr = JSON.stringify($(branchSelect).val());

            //document.getElementById(branchDepId).value = $(branchSelect).val();
            document.getElementById(branchDepId).value = depArr;
        }
    }

    //console.log($('#departments1').val())

    var parent_fieldset = $('.f1 #thirdButton').parents('fieldset');
    var if_next = true;

    parent_fieldset.find('input[type="text"], input[type="password"], select').each(function() {

        if( ($(this).val() == "" && $(this)[0].nodeName!="SELECT") || ($(this).val().length == 0  && departments.length != 0)) {

            $(this).addClass('input-error');

            if($(this)[0].nodeName=="INPUT") if_next = false


            if(branchesNum < 2 && $(this)[0].id!='departments2') if_next = false

            if(branchesNum > 1){
                if($(this)[0].nodeName=="SELECT"){
                    if_next = false
                }
            }

    	}
    	else {
    		$(this).removeClass('input-error');
    	}

    });


    if(if_next){
        //console.log("hi reem from ajax")
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        data = $('#companyForm').serialize();
        //console.log(data)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        $.ajax({
            beforeSend: function(){
                //$('#LoadingModal').modal('show');
                document.getElementById('loaderContainer').hidden = false;
            },
            type: 'POST',
            url: "{{ route('admin.companies.createBranches') }}",
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log(response.data)
            },
            complete: function(){
                document.getElementById('loaderContainer').hidden = true;
                document.querySelector('#thirdStepButton').click();

                var editLink = "{{ route('admin.companies.edit', ['id' => 'company_id']) }}";
                editLink = editLink.replace('company_id', document.getElementById("company_id").value);
                document.getElementById("editCompanyLink").setAttribute("href",editLink);


                //to get companies categories
                categories = {!! json_encode($categories, JSON_HEX_APOS) !!};

                //console.log(categories)

                const arrayOfCategories = categories.map(obj => [obj.cc_id, obj.cc_name]);

                //console.log(arrayOfCategories);

                //ccIdToFind = 8;

                categIdToFind = document.getElementById('c_category').value;
                var categName;
                for (const array of arrayOfCategories) {
                    const [ccId, name] = array;
                    if (ccId+"" === categIdToFind) {
                        categName = name;
                        break; // Stop the loop once a match is found
                    }
                }

                //set step 4 values - summary tab
                document.getElementById("company_name_summary").innerHTML = "{{__('translate.Company Name')}} : "+document.getElementById("c_name").value;
                $('#manager_summary').html(document.getElementById("name").value);
                $('#email_sammury').html(document.getElementById("email").value);
                $('#phone_summary').html(document.getElementById("phoneNum").value);
                $('#address_summary').html(document.getElementById("address").value);
                $('#category_summary').html(categName);
                $('#type_summary').html(document.getElementById("c_type").value == 1 ? "{{__('translate.Public Sector')}}" : "{{__('translate.Private Sector')}}");


                x = "";
                if(document.getElementById("c_description").value != ""){
                    document.getElementById("description_summary_area").hidden = false;
                    $('#description_summary').html(document.getElementById("c_description").value)
                }

                if(document.getElementById("phone2_1").value != ""){
                    document.getElementById("phone2_summary_area").hidden = false;
                    $('#phone2_summary').html(document.getElementById("phone2_1").value);
                }

                if(document.getElementById("c_website").value != ""){
                    document.getElementById("website_summary_area").hidden = false;
                    $('#website_summary').html(document.getElementById("c_website").value);
                }


                //to list all departments for company
                if(departments.length!=0){


                    //to set main branch departments
                    mb_departments = $('#departments1').val();
                    for(d=0;d<mb_departments.length;d++){
                        x += `${departments[mb_departments[d]]}`
                        if(d < mb_departments.length-1){
                            x += "، "
                        }
                    }
                    document.getElementById("main_branch_departments_area").hidden = false;
                    $('#main_branch_departments').html(x);


                    //to list departments for this company
                    x="";
                    for(i=0;i<departments.length;i++){
                        d_name = departments[i];
                        x += `${d_name}`
                        if(i < departments.length-1){
                            x += "، "
                        }
                    }
                    $('#departments_summary').html(x);
                    document.getElementById("departments_table").hidden = false;

                }


                branchesNum = document.getElementById('branchesNum').value;

                //to list branches in summary page for this company
                if(branchesNum > 1){
                    x=""
                    tempB = "";
                    //document.getElementById("branches_summary_area").hidden = false;
                    for(i=1;i<branchesNum;i++){

                        branchSelect = `#departments${i+1}`;
                        branch_name = "";
                        branch_id = `address${i+1}`
                        branch_address = document.getElementById(branch_id).value;
                        branch_phone1 = document.getElementById(`phone1_${i+1}`).value;
                        branch_phone2 = document.getElementById(`phone2_${i+1}`).value;

                        if(departments.length!=0){
                            for(r=0;r<$(branchSelect).val().length;r++){
                                temp = $(branchSelect).val()
                                tempB += `${departments[temp[r]]}`
                                if(r < $(branchSelect).val().length - 1){
                                    tempB += `، `
                                }
                            }
                        }

                        x += `<div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="background-color: #ecf0ef82;" colspan="2">{{__('translate.Branch')}} ${i+1}</th>
                                    </tr>
                                    <tbody>
                                        <tr>
                                            <td class="col-md-3">{{__('translate.Phone 1')}}</td>
                                            <td>${branch_phone1}</td>
                                        </tr>
                                        <tr id="branch_phone2_area" ${branch_phone2 == "" ? 'hidden' : ''}>
                                            <td class="col-md-3">{{__('translate.Phone 2')}}</td>
                                            <td id="branch_phone2">${branch_phone2}</td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-3">{{__('translate.Branch Address')}}</td>
                                            <td>${branch_address}</td>
                                        </tr>
                                        <tr id="branch_departments_area" ${departments.length == 0 ? 'hidden' : ''}>
                                            <td class="col-md-3">{{__('translate.Branch Departments')}}</td>
                                            <td id="branch_departments">${tempB}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <br>`

                        tempB = "";
                    }

                    $('#branches_summary').html(x);
                }

                $('.ribbon-wrapper').fadeOut('slow');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}

companyForm.addEventListener("submit", (e) => {
    e.preventDefault();
    data = $('#companyForm').serialize();
    //console.log(data)

});


function addDepartment(){
    departmentName = document.getElementById('d_name').value;
    if(departmentName!="" && !departments.includes(departmentName)){
        departments.push(departmentName);
        x = "";
        for(i=0;i<departments.length;i++){
            // x = x + '<div class="row mb-2"><div class="col-md-6"><h5>'+departments[i]+
            // '</h5></div><div class="col-md-2"><button class="btn btn-danger" onclick="deleteDepartment('+i+')"><i class="fa fa-trash"></i></button></div></div>'
            x = x + `<div class="card mb-3 shadow-sm shadow-showcase" style="border-radius: 10px;background-color: #e6edef;">
                                        <div class="card-body d-flex justify-content-between" style="border-radius: 10px; padding:10px!important;">
                                            <span>${departments[i]}</span>
                                            <span onclick="deleteDepartment('${i}')"><i class="fa fa-trash"></i></span>
                                        </div>
                    </div>`
        }
        $('#departmentsArea').html(x);
    }

    document.getElementById('d_name').value = "";
}

function deleteDepartment(i){
    departments.splice(i, 1);
    x = "";
    for(i=0;i<departments.length;i++){
        //x = x + '<div class="row mb-2"><div class="col-md-6"><h5>'+departments[i]+
        //'</h5></div><div class="col-md-2"><button class="btn btn-danger" onclick="deleteDepartment('+i+')"><i class="fa fa-trash"></i></button></div></div>'
        x = x + `<div class="card mb-3 shadow-sm shadow-showcase" style="border-radius: 10px;background-color: #e6edef;">
                                        <div class="card-body d-flex justify-content-between" style="border-radius: 10px; padding:10px!important;">
                                            <span>${departments[i]}</span>
                                            <span onclick="deleteDepartment('${i}')"><i class="fa fa-trash"></i></span>
                                        </div>
                    </div>`
    }
    $('#departmentsArea').html(x);
}

function departmentStep(){

    branchesNumber = document.getElementById('branchesNum').value;

    //to set department values to input and send it to the controller
    document.getElementById('departmentsList').value = JSON.stringify(departments);

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })

    $.ajax({
        beforeSend: function(){
            //$('#LoadingModal').modal('show');
            document.getElementById('loaderContainer').hidden = false;
        },
        type: 'POST',
        url: "{{ route('admin.companies.createDepartments') }}",
        data: {
            'departments': JSON.stringify(departments),
            'companyID': document.getElementById('company_id').value
        },
        dataType: 'json',
        success: function(response) {
            //console.log(response.data)

            x="";

            if(branchesNumber>1){

                document.getElementById('secondBranch').hidden = false;

                for(i=2;i<branchesNumber;i += 2){

                        x += `<div class="row">
                            <div class="col-md-6">
                            <div class="ribbon-wrapper card shadow-sm" style="border-radius: 5px;">
                            <div class="card-body">
                                <div class="ribbon ribbon-primary ribbon-right">{{__('translate.Branch')}} ${i+1}</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone1_${i+1}">{{__('translate.Phone 1')}}<span style="color: red">*</span></label>
                                            <input class="f1-last-name form-control" id="phone1_${i+1}" type="text" name="phone1_${i+1}" required="" oninput="validateInput(this)">
                                            <div id="errorMessage_phone1_${i+1}" style="color:#dc3545" class="error-message"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone2_${i+1}">{{__('translate.Phone 2')}}</label>
                                            <input class="f1-last-name form-control" id="phone2_${i+1}" name="phone2_${i+1}" oninput="validateInput(this)">
                                            <div id="errorMessage_phone2_${i+1}" style="color:#dc3545" class="error-message"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for=address${i+1}">{{__('translate.Branch Address')}}<span style="color: red">*</span></label>
                                            <input class="f1-last-name form-control" id="address${i+1}" type="text" name="address${i+1}" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="departments_group${i+1}" hidden>
                                            <label for="departments${i+1}">{{__('translate.Branch Departments')}} <span style="color: red">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" multiple="multiple" id="departments${i+1}" multiple></select>
                                        </div>
                                    </div>

                                    <input hidden id="department_for_${i+1}" name="department_for_${i+1}">

                                </div>
                            </div>
                            </div>
                            </div>`;


                        // Check if there is another branch to add in the same row
                        if (i + 2 <= branchesNumber) {
                            x += `<div class="col-md-6">
                            <div class="ribbon-wrapper card shadow-sm" style="border-radius: 5px;">
                            <div class="card-body">
                                <div class="ribbon ribbon-primary ribbon-right">{{__('translate.Branch')}} ${i+2}</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone1_${i+2}">{{__('translate.Phone 1')}}</label>
                                            <input class="f1-last-name form-control" id="phone1_${i+2}" type="text" name="phone1_${i+2}" required="" oninput="validateInput(this)">
                                            <div id="errorMessage_phone1_${i+2}" style="color:#dc3545" class="error-message"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone2_${i+2}">{{__('translate.Phone 2')}}</label>
                                            <input class="f1-last-name form-control" id="phone2_${i+2}" name="phone2_${i+2}" required="" oninput="validateInput(this)">
                                            <div id="errorMessage_phone2_${i+2}" style="color:#dc3545" class="error-message"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for=address${i+2}">{{__('translate.Branch Address')}}</label>
                                            <input class="f1-last-name form-control" id="address${i+2}" type="text" name="address${i+2}" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="departments_group${i+2}" hidden>
                                            <label for="departments${i+2}">{{__('translate.Branch Departments')}}</label>
                                            <select class="js-example-basic-single col-sm-12" multiple="multiple" id="departments${i+2}" multiple></select>
                                        </div>
                                    </div>

                                    <input hidden id="department_for_${i+2}" name="department_for_${i+2}">

                                </div>
                            </div>
                            </div>
                                </div>`;
                        }

                        x += '</div>';
                }


                $('#branches').html(x);


                //to load script after adding branches
                loadScript("{{ asset('assets/js/select2/select2-custom.js') }}", function() {});
                loadScript("{{ asset('assets/js/select2/select2.full.min.js') }}", function() {});


            }


            if(departments.length!=0){

                ////////////to set departments for branches/////////////
                if(branchesNumber>=1){
                    //to show department area in each branch
                    for(var i = 0; i < branchesNumber; i++){
                        departmentArea = `departments_group${i+1}`
                        document.getElementById(departmentArea).hidden = false;

                        departmentSelect = `departments${i+1}`
                        var multiselect = document.getElementById(departmentSelect);

                        var options = departments;

                        for (var r = 0; r < options.length; r++) {
                        var option = document.createElement("option");
                        option.text = options[r];
                        option.value = r;
                        multiselect.add(option);
                        }

                    }
                }
                ////////////////////////////////////////////////////////////////



            }

            document.getElementById('loaderContainer').hidden = true;
            document.querySelector('#departmentStepButton').click();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });


}


//to load script again
function loadScript(src, callback) {
    var script = document.createElement('script');
    script.src = src;
    script.onload = callback;
    document.head.appendChild(script);
}

</script>


@endsection
