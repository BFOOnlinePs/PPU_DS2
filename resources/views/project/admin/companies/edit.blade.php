@extends('layouts.app')
@section('title')
{{__('translate.Edit Company')}}{{--تعديل شركة--}}
@endsection
@section('header_title')
     {{__('translate.Edit Company')}}{{-- تعديل شركة --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('admin.companies.index') }}">{{__('translate.Companies Management')}}{{--إدارة الشركات--}}</a>
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
    .c_name{
      position: absolute;
      top: 20px; /* Adjust the top position as needed */
      right: 40px; /* Adjust the left position as needed */
      padding-bottom:10px;
      margin-bottom: 5px; /* Remove default margin-bottom */
      }
</style>

@endsection
@section('content')

<div class="card" style="padding-left:0px; padding-right:0px; padding-top:0px">
{{-- <div style="position: absolute; top: 10px; right: 40px;"> <h2>{{$company->c_name}}</h2></div> --}}
<!-- <h3 class="c_name"> {{$company->c_name}}</h3> -->

    {{-- <div class="card-header pb-0">
        <h1>إضافة شركة</h1>
    </div> --}}
    <div class="card-body" >
    <div class="row text-center">
            <h2><b>{{$company->c_name}}</b></h2>
        </div>

        <!--loading whole page-->
        <div class="loader-container loader-box" id="loaderContainer" hidden>
            <div class="loader-3"></div>
        </div>
        <!--//////////////////-->
        <div>

        </div>
        <div class="f1"  id="companyForm">


<div class="stepwizard mt-5">
                <div class="stepwizard-row setup-panel">
                  <div class="stepwizard-step"><button class="btn btn-primary" id="btn1" onclick="info()"><i class="fa fa-file-text-o" ></i></button>
                    <p>{{__("translate.Company Information")}}{{--معلومات الشركة--}}</p>
                  </div>
                  <div class="stepwizard-step"><button class="btn btn-light" id="btn2" onclick="department()"><i class="fa fa-th-large" ></i></button>
                    <p>{{__("translate.Company Departments")}}{{--أقسام الشركة--}}</p>
                  </div>
                  <div class="stepwizard-step"><button class="btn btn-light" id="btn3" onclick="branch()"><i class="fa fa-sitemap" ></i></button>
                    <p>{{__("translate.Company Branches")}}{{--فروع الشركة--}}</p>
                  </div>
                </div>
            </div>






            <div id ="info">
                <form id="EditCompanyInfo" method="post">
                    @csrf
                <div id="editCompanyForm">
            <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="f1-first-name"> {{__('translate.Company Name')}}  <span style="color: red">*</span> {{-- اسم الشركة --}}</label>

                            <div class="input-container">
                                <i id="ok_icon" class="icon fa fa-check" style="color:#ef681a" hidden></i>
                                <i id="search_icon" class="icon_spinner fa fa-spin fa-refresh" hidden></i>
                                <input class="form-control required" type="text" id="c_name" name="c_name" value="{{$company->c_name}}"  onkeyup="checkCompany(this.value)">
                            </div>

                            <div id="similarCompanyMessage" style="color:#dc3545" hidden>
                                <span>{{__("translate.There is company with the same name you entered,")}}'{{--يوجد شركة بنفس الاسم الذي قمت بادخاله،--}}</span>
                                <u><a id="companyLink" style="color:#dc3545">{{__("translate.To move to the edit click here")}}'{{--للانتقال إلى التعديل قم بالضغط هنا--}}</a></u>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Owner')}}  <span style="color: red">*</span> {{-- الشخص المسؤول --}}</label>
                            <input class="f1-last-name form-control required" id="name" type="text" name="name" value="{{$company->manager->name}}" >
                        </div>
                    </div>

              <div class="col-md-4">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Company Phone Number')}} <span style="color: red">*</span>{{-- رقم هاتف الشركة --}}</label>
                            <input class="f1-last-name form-control required" id="phoneNum" type="text" name="phoneNum" value="{{$company->manager->u_phone1}}" oninput="validateInput(this)">
                            <div id="errorMessage_phoneNum" style="color:#dc3545" class="error-message"></div>
                        </div>


                </div>

    </div>





    <div class="row">
                <div class="col-md-4">
                        <div class=" mb-3 form-group">
                            <label for="f1-first-name"> {{__('translate.Email')}} <span style="color: red">*</span>  {{-- البريد الإلكتروني --}} </label>
                            {{--<input class="form-control required" id="email" type="text" name="email" value="{{$company->manager->email}}"  oninput="validateEmail(this)">--}}
                            <div class="input-container">
                                <i id="email_ok_icon" class="icon fa fa-check" style="color:#ef681a" hidden></i>
                                <i id="email_search_icon" class="icon_spinner fa fa-spin fa-refresh" hidden></i>
                                <input class="form-control required" id="email" type="text" name="email" value="{{$company->manager->email}}" oninput="validateEmail(this)">
                            </div>
                            <div id="similarEmailMessage" style="color:#dc3545" hidden>
                                <span>{{__("translate.Email has already been used")}}{{--البريد الإلكتروني موجود بالفعل--}}</span>
                            </div>
                        </div>

                 </div>
                       <div class="col-md-4">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Password')}} {{-- كلمة المرور --}}</label>
                            <input class="f1-password form-control " id="password" type="password" name="password" >
                        </div>
                    </div>
                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="f1-last-name">{{__('translate.Company Address')}}<span style="color: red">*</span>{{-- عنوان الشركة --}} </label>
                                <input class="f1-last-name form-control required" id="address" type="text" name="address" value="{{$company->manager->u_address}}">
                            </div>
                    </div>
                </div>



                <div class="row">


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Company Type')}} <span style="color: red">*</span>{{-- نوع الشركة --}}</label>
                            <select id="c_type" name="c_type" class="form-control btn-square" value="{{$company->c_type}}">
                                <option @if($company->c_type== 1) selected @endif value="1">{{__('translate.Public Sector')}}{{-- قطاع عام --}}</option>
                                <option @if($company->c_type== 2) selected @endif value="2">{{__('translate.Private Sector')}}{{-- قطاع خاص --}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Company Category')}} <span style="color: red">*</span> {{-- تصنيف الشركة --}}</label>
                            <select id="c_category" name="c_category" class="form-control btn-square" value="{{$company->c_category_id}}">
                                @foreach($categories as $key)
                                   <option value="{{$key->cc_id}}" @if($company->c_category_id == $key->cc_id) selected @endif>{{$key->cc_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
   <div class="col-md-4">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Website')}}{{-- الموقع الإلكتروني --}}</label>
                            <input  class="form-control" id="c_website" name="c_website" value="{{$company->c_website}}" oninput="validateArabicText(this)">
                        </div>
                    </div>


                </div>
                 <div class="row">



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="f1-last-name">{{__('translate.Company Description')}}{{-- وصف الشركة --}}</label>
                            <textarea  class="form-control" id="c_description" name="c_description" rows="6" >{{$company->c_description}}</textarea>
                        </div>
                    </div>



    </div>
    </div>


                <input hidden id="manager_id" name="manager_id" value="{{$company->manager->u_id}}">
                <input hidden id="c_id" name="c_id" value="{{$company->c_id}}">
                <div class="f1-buttons">
                    <button type="submit" id="submit_editCompany" class="btn btn-primary">{{__('translate.Edit')}}{{-- تعديل --}}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                 </div>

    </form>
                </div>









                <div id="department" hidden>
                <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                    <form id="addDepartment" method="post">

                                        <label for="f1-first-name"> {{__('translate.Department Name')}}{{-- اسم القسم --}}</label>
                                        <input class="form-control" id="d_name" name="d_name">
                                        <input class="form-control" id="d_company_id" name="d_company_id" value="{{$company->c_id}}" hidden>
    </form>
    <div id="similarCompanyDepMessage" style="color:#dc3545" hidden>
                                <span>{{__("translate.There is department with the same name you entered")}}{{--يوجد قسم بنفس الاسم الذي قمت بادخاله--}}</span>

                            </div>

                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-top: 26px;">
                                    <button class="btn btn-info" type="button" onclick="addDepartment()">{{__('translate.Add Department')}}{{-- إضافة القسم --}}</button>
                                </div>
                            </div>
                            <div class="row" id="departmentsArea">

                            </div>
                        </div>


                <!--أقسام الشركة-->


                <div class="col-md-7" >
                    <div class="card card-absolute"  id="departments_summary_area_company" >
                        <div class="card-body">
                          <div class="card-header bg-primary">
                            <h5 class="text-white">{{__("translate.Company Departments")}}{{--أقسام الشركة--}}</h5>
                          </div>

                          <div class="card-body">
                            <div id="departments">
                              <div class="row">
                                  <form id="EditCompanyDepartments" method="post">
                                    @csrf
                                  <div id="DepartmentList">
                                  <input id="companyDepartments" name="companyDepartments" value="{{$companyDepartments}}" hidden>
                                      @foreach($companyDepartments as $key1)
                                          <input hidden id="d_id" name="d_id" value="{{$key1->d_id}}">
                                          <div class="col-md-8">
                                              <input class="f1-last-name form-control" name="d_name_{{$key1->d_id}}" id="d_name_{{$key1->d_id}}" value="{{$key1->d_name}}">
                                          </div>
                                          <br>
                                      @endforeach
                                      <input hidden id="c_id" name="c_id" value="{{$company->c_id}}">
                                  </div>

                                  <div class="f1-buttons" id="formButtons" hidden>
                                      <button type="submit" id="submit_departments" class="btn btn-primary">{{__('translate.Edit')}}{{-- تعديل --}}</button>
                                  </div>


                                  </form>
                              </div>

                              <div id="addedDepartment" >
                              </div>
                          </div>
                          </div>

                            </div>
                        </div>
                    </div>


    </div>









            <div id="branch" hidden>
            <div>
    <button class="btn btn-primary  mb-2 btn-s" type="button" onclick='addBranch()'><span class="fa fa-plus"></span> {{__('translate.Add Branch')}}{{-- إضافة فرع --}}</button>
</div>
    <div class="row" id="companyBranches">
    <input hidden id="branches" name="branches" value="{{$company->companyBranch}}">
    <input id="companyDepartments1" name="companyDepartments1" value="{{$companyDepartments}}" hidden>

            @foreach($company->companyBranch as $key)


                    <div class="col-md-6">
                        <div class="ribbon-wrapper card shadow-sm" style="border-radius: 5px;">
                          <div class="card-body">
                            <form id="EditCompanyBranches_{{$key->b_id}}" method="POST">
                            @csrf
                            <div class="ribbon ribbon-primary ribbon-right">@if($key->b_main_branch == 1) {{__('translate.Main Branch')}}{{-- الفرع الرئيسي --}} @else {{__('translate.Branch')}} {{-- الفرع --}} {{ $loop->index  ++  }}@endif</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone1">{{__('translate.Phone 1')}}<span style="color: red">*</span>{{-- هاتف 1 --}}</label>
                                        <input class="f1-last-name form-control" id="phone1_{{$key->b_id}}" type="text" name="phone1_{{$key->b_id}}" value="{{$key->b_phone1}}" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone2">{{__('translate.Phone 2')}}{{-- هاتف 2 --}}</label>
                                        <input class="f1-last-name form-control" id="phone2_{{$key->b_id}}"  name="phone2_{{$key->b_id}}"  value="{{$key->b_phone2}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">{{__('translate.Branch Address')}}{{-- عنوان الفرع --}}</label>
                                        <input class="f1-last-name form-control" id="address_{{$key->b_id}}" type="text" name="address_{{$key->b_id}}"   value="{{$key->b_address}}" >
                                    </div>
                                </div>
                                <input hidden id="department_for_1_{{$key->b_id}}" name="department_for_1_{{$key->b_id}}">
                                <input hidden id="c_id_{{$key->b_id}}" name="c_id_{{$key->b_id}}" value="{{$company->c_id}}">
                                <input hidden id="manager_id_{{$key->b_id}}" name="manager_id_{{$key->b_id}}" value="{{$company->c_manager_id}}">
                                <input hidden id="b_id" name="b_id" value="{{$key->b_id}}">
                                <input hidden id="companyDep_{{$key->b_id}}" name="companyDep_{{$key->b_id}}" value="{{$key->companyBranchDepartments}}">
                                <input hidden id="departmentSelectedList_{{$key->b_id}}" name="departmentSelectedList_{{$key->b_id}}" >
                                <div class="col-md-6">
                                    <div class="form-group" id="departments_group1_{{$key->b_id}}" >
                                        <input hidden id="branchesNumber_{{$key->b_id}}" name="branchedNumber_{{$key->b_id}}" value="{{count($company->companyBranch)}}">
                                        <label for="departments_{{$key->b_id}}">{{__('translate.Branch Departments')}}{{-- أقسام الفرع --}}</label>
                                        <select class="js-example-basic-single col-sm-12" multiple="multiple" id="departments_{{$key->b_id}}"  multiple>
                                        @foreach($companyDepartments as $key2)
                                                      <option value="{{$key2->d_id}}"@foreach($key->companyBranchDepartments as $key3) @if($key3->cbd_d_id==$key2->d_id) selected @endif  @endforeach >{{$key2->d_name}} </option>
                                                   @endforeach
                                                </select>
                                    </div>
                                </div>



                            </div>
                <div class="f1-buttons" >
                    <button  id="submit_{{$key->b_id}}" onclick="submitEditCompanyBranches({{ $key->b_id}})" class="btn btn-primary" type="button">{{__('translate.Edit')}}{{-- تعديل --}}</button>
                                </div>
  </form>
                          </div>
                        </div>
                    </div>
            @endforeach
     </div>



    </div>
    </div>


    </div>






    @include('project.admin.companies.modals.uncompletedCompanyModal')

    @include('project.admin.companies.modals.addBranchModal')

    @include('layouts.loader')
</div>

@endsection


@section('script')
<!-- <script src="{{ asset('assets/js/form-wizard/form-wizard-three.js') }}"></script>
<script src="{{asset('assets/js/form-wizard/jquery.backstretch.min.js')}}"></script> -->

<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>



<script>
let companyForm = document.getElementById("companyForm");
let EditCompanyInfoForm = document.getElementById("EditCompanyInfo");
let EditCompanyDepartments = document.getElementById("EditCompanyDepartments");
let addBranchForm = document.getElementById("addBranchForm");
let editBranchForm = document.getElementById("EditCompanyBranches");
let companyName;
let company_id;
let branchesNumber = 1;
let departments = JSON.parse(document.getElementById('companyDepartments').value);
let uncompletedCompanySize = 0;
let uncompletedCompany;
let existEmail = document.getElementById('email').value;
let branches = JSON.parse(document.getElementById('branches').value);

let language = document.documentElement.lang

$(document).ready(function () {
    var iconSpinners = document.querySelectorAll('.icon_spinner');
    var icons = document.querySelectorAll('.icon');

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
});

 // $(document).ready(function() {
//     if (document.getElementById('branches').value != null) {

//         branches.forEach(function(branch) {
//             $('#departments_' + branch.b_id).on('select2:open', function(event) {
//                 console.log('Select2 dropdown opened!');

//                 // Your custom code when the dropdown is opened

// //                 var multiselect = this;
// //                  $(multiselect).empty();
// //                 console.log("hi");
// //                 console.log(multiselect);
// //                 console.log(branch.b_id);
// //                 var companyDep='companyDep_'+branch.b_id;
// //                 var companyDepValues = JSON.parse(document.getElementById(companyDep).value);
// //                 console.log(companyDepValues);
// //                 var options = departments; // Assuming departments is defined somewhere in your code

// //                 for (var r = 0; r < options.length; r++) {
// //                     var option = document.createElement("option");
// //                    option.text = options[r].d_name;
// //                     console.log("f");
// //                     console.log(options[r]);
// //                     option.value = options[r].d_id; // Use the actual value from options array
// //                     // option.selected = true;
// //                     $(multiselect).append(option);
// //                     for(x of companyDepValues ){

// // if(option.value == x.d_id)
// // option.selected = true;
// //                     }

// //                 }
//             });
//         });
//     }
// });

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
    document.getElementById('submit_editCompany').disabled = true;
    let email = input.value.trim();

    if(email==""){
       input.style.borderColor = "";
       document.getElementById('submit_editCompany').disabled = false;
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
                    document.getElementById('submit_editCompany').disabled = true;
                    document.getElementById('similarEmailMessage').hidden = true;
                    document.getElementById('email_ok_icon').hidden = true;
                },
                url: "{{ route('admin.companies.check_email_not_duplicate_edit') }}",
                method: "post",
                data: {
                    'email': email,
                    'existEmail':existEmail
                },
                success: function(response) {
                    console.log(response.user_email)
                    //continueValidation = true;
                    if(response.status == 'true'){
                        document.getElementById('submit_editCompany').disabled = true;
                        document.getElementById('email_search_icon').hidden = true;
                        document.getElementById('email_ok_icon').hidden = true;
                        document.getElementById('similarEmailMessage').hidden = false;
                    }else{
                        document.getElementById('submit_editCompany').disabled = false;
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


function validateInput(input) {

    inputID = input.id;

    // Remove non-numeric characters
    let sanitizedValue = input.value.replace(/\D/g, '');
    const errorMessageElement = document.getElementById(`errorMessage_${inputID}`);


    if(input.value==""){
        input.style.borderColor = "";
        errorMessageElement.textContent = '';
        document.getElementById('submit_editCompany').disabled = false;
    }else{


        // Limit to 10 digits
        sanitizedValue = sanitizedValue.slice(0, 10);

        // Update the input value
        input.value = sanitizedValue;

        if (sanitizedValue.length < 10) {
            // Invalid input
            //input.setCustomValidity('Please enter a 10-digit number.');
            //input.style.borderColor = "#dc3545";
            errorMessageElement.textContent = 'الرجاء إدخال رقم مكون من 10 خانات';
            document.getElementById('submit_editCompany').disabled = true;
        } else {
            //input.style.borderColor = "";
            errorMessageElement.textContent = '';
            document.getElementById('submit_editCompany').disabled = false;
        }

    }

}
//noor
function updateButtonClasses(activeBtnId) {
    const buttons = document.querySelectorAll('.stepwizard-step button');

    buttons.forEach((button) => {
      button.classList.remove('btn-primary');
      button.classList.add('btn-light');
    });

    const activeBtn = document.getElementById(activeBtnId);
    activeBtn.classList.remove('btn-light');
    activeBtn.classList.add('btn-primary');
}
function info(){
    updateButtonClasses('btn1');

    document.getElementById('info').hidden = false ;
    document.getElementById('department').hidden = true ;
    document.getElementById('branch').hidden = true ;
}
function department(){
    updateButtonClasses('btn2');

    document.getElementById('info').hidden = true ;
    document.getElementById('department').hidden = false ;
    document.getElementById('branch').hidden = true ;
    if(document.getElementById('companyDepartments').value != null){
        document.getElementById('formButtons').hidden=false;
    }
    console.log("document.getElementById('companyDepartments').value")
    console.log(document.getElementById('companyDepartments').value)
    // var departmentsArray = JSON.parse(document.getElementById('companyDepartments').value);
    // console.log("dataArray")
    // console.log(departmentsArray)
    // for(i=0;i<departmentsArray.length;i++){
    //     departments.push(departmentsArray[i]);
    // }
 console.log("departments")
    console.log(departments)

}
function branch(){
    updateButtonClasses('btn3');

    document.getElementById('info').hidden = true ;
    document.getElementById('department').hidden = true ;
    document.getElementById('branch').hidden = false ;
    console.log("fbbb")
    console.log(departments)

        //  var multiselect = document.getElementById('departments1');
        //  var options = departments;
        //  for (var r = 0; r < options.length; r++) {
        //             var option = document.createElement("option");
        //             option.text = options[r].d_name;
        //             option.value = r;
        //             multiselect.add(option);
        //  }



}
function addBranch(){



         $('#AddBranchModal').modal('show');
        var multiselect = document.getElementById('departments_ajax');
       $(multiselect).empty();
        console.log("hihi")
        console.log(multiselect)
        var options = JSON.parse(document.getElementById('companyDepartments1').value);
         for (var r = 0; r < options.length; r++) {
                    var option = document.createElement("option");
                    option.text = options[r].d_name;
                    option.value = r;
                    multiselect.add(option);
         }

}

EditCompanyInfoForm.addEventListener("submit", (e) => {
            e.preventDefault();
            data = $('#EditCompanyInfo').serialize();
            console.log(data);
            if_submit = true;
            const requiredInputs = document.querySelectorAll('.required');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            requiredInputs.forEach(function(currentElement) {
        // Do something with the current element
        console.log(currentElement.value);  // You can access properties like 'value', 'id', etc.

        if(currentElement.value==""){
            if_submit = false;
            $(currentElement).addClass('input-error');
        }
        else{
            $(currentElement).removeClass('input-error');
        }

    });



    if(if_submit){

            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Send an AJAX request
            $.ajax({
                //new
                beforeSend: function(){
                    $('#LoadingModal').modal('show');
                },
                type: 'POST',
                url: "{{ route('admin.companies.update') }}",
                data: data,
                success: function(response) {
                    $('#editCompanyForm').html(response.view);
                    $('#companyBranches').html(response.branchView);

                    if(language=='ar'){
                        $('#editCompanyForm .icon_spinner').css({
                            'position': 'absolute',
                            'top': '30%',
                            'transform': 'translateY(-50%)',
                            'left': '10px',
                        });
                        $('#editCompanyForm .icon').css({
                            'position': 'absolute',
                            'top': '50%',
                            'transform': 'translateY(-50%)',
                            'left': '10px',
                        });
                    }else{
                        $('#editCompanyForm .icon_spinner').css({
                            'position': 'absolute',
                            'top': '30%',
                            'transform': 'translateY(-50%)',
                            'right': '10px',
                        });
                        $('#editCompanyForm .icon').css({
                            'position': 'absolute',
                            'top': '50%',
                            'transform': 'translateY(-50%)',
                            'right': '10px',
                        });
                    }


                },
                //new
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        });
        addBranchForm.addEventListener("submit", (e) => {
            e.preventDefault();
            depArr=[];
            ms_departments = $('#departments1').val();
            console.log(ms_departments)
            depArr = JSON.stringify($('#departments1').val());

            console.log("ms_departments")

                document.getElementById("departmentsList").value = depArr;



            console.log("data55555")
            data = $('#addBranchForm').serialize();
            console.log(data);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Send an AJAX request
            $.ajax({
                //new
                beforeSend: function(){

                    $('#AddBranchModal').modal('hide');
                    $('#LoadingModal').modal('show');
                    alert(depArr);

                },
                type: 'POST',
                url: "{{ route('admin.companies.createBranchesEdit') }}",
                data: data,
               // dataType: 'json',
                success: function(response) {
                     $('#companyBranches').html(response.view);
                     document.getElementById('phone1').value ="";
                     document.getElementById('phone2').value ="";
                     document.getElementById('address_addB').value ="";
                     document.getElementById('departments1').value ="";
                },
                //new
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        });
        function submitEditCompanyBranches(data){
            console.log("data")
            console.log(data)
            editBranchFormID="EditCompanyBranches_"+data;
            editBranchForm =document.getElementById(editBranchFormID);
            editBranchFormIDser='#'+editBranchFormID;
            // e.preventDefault();
            depArr=[];
            selectedDepartments = $('#departments_'+data).val();
            console.log(selectedDepartments)
            depArr = JSON.stringify($('#departments_'+data).val());
            console.log("selectedDepartments")
            console.log(selectedDepartments)
            console.log(depArr)
             document.getElementById("departmentSelectedList_"+data).value = depArr;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            data = $(editBranchFormIDser).serialize();
            console.log(data);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Send an AJAX request
            $.ajax({
                //new
                beforeSend: function(){


                    $('#LoadingModal').modal('show');
                },
                type: 'POST',
                url: "{{ route('admin.companies.updateBranches') }}",
                data: data,
                dataType: 'json',
                success: function(response) {
                     $('#companyBranches').html(response.view);
                     $('#editCompanyForm').html(response.companyInfoView);



                },
                //new
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });


        }



        EditCompanyDepartments.addEventListener("submit", (e) => {
            e.preventDefault();
            data = $('#EditCompanyDepartments').serialize();
            console.log(data);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Send an AJAX request
            $.ajax({
                //new
                beforeSend: function(){
                    $('#LoadingModal').modal('show');
                },
                type: 'POST',
                url: "{{ route('admin.companies.updateDepartments') }}",
                data: data,
                success: function(response) {


                    $('#DepartmentList').html(response.view);
                    $('#companyBranches').html(response.branchView);


                },
                //new
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

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


    branchesNum = document.getElementById('branchesNum').value;
    if(branchesNum<2){
        //cause adress2 and phone1 are required inputs in the wizard and it will not continue to the
        //next step even they are hidden, so when choose 1 branch they are actully exists but whithout values
        //so the wizard will not continue to next step
        //so this is the temp solution for this issue
        document.getElementById('address2').value = 0;
        document.getElementById('phone1_2').value = 0;
    }

    $('#uncompletedCompanyModal').modal('hide');

}

function checkCompany(data){

    document.getElementById('ok_icon').hidden = true;


    if(data!="" ){
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

            if(response.data!=null && response.company_name !=  document.getElementById('c_name').value){

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

    if(uncompletedCompanySize!=0){
        console.log("hi reem from first step but with complete company")
        document.querySelector('#firstStepButton').click();
    }else{
        console.log("hi reem from first step but with new company")
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        data = $('#companyForm').serialize();

        console.log(data);

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
                console.log(response.company_id)
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
    }

    //document.getElementById('companyName').value = "companyName";
    //document.querySelector('#firstStepButton').click();

}

function secondStep(){
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    data = $('#companyForm').serialize();

    // if(document.getElementById('c_website').value=="" && document.getElementById('c_description').value==""&&uncompletedCompanySize==0){
    //     document.querySelector('#secondStepButton').click();
    // }else{
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

    //}

    //document.querySelector('#secondStepButton').click();

}





// companyForm.addEventListener("submit", (e) => {
//     e.preventDefault();
//     data = $('#companyForm').serialize();
//     //console.log(data)

// });

function addDepartment()
  {
    document.getElementById('similarCompanyDepMessage').hidden = true;
    departmentName = document.getElementById('d_name').value;
    companyDepartments=JSON.parse(document.getElementById('companyDepartments').value);
    let departmentNames =  companyDepartments.map(companyDepartment => companyDepartment.d_name);
console.log("departmentNames")
    console.log(departmentNames)

    if(departmentName!="" && !departmentNames.includes(departmentName))
    {
        departmentNames.push(departmentName);
        data=$('#addDepartment').serialize();
        console.log("departmentNames")
    console.log(departmentNames)
    var csrfToken = $('meta[name="csrf-token"]').attr('content');


// Send an AJAX request with the CSRF token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrfToken
    }
});

// Send an AJAX request
$.ajax({
    //new
    beforeSend: function(){

        $('#LoadingModal').modal('show');
    },
    type: 'POST',
    url: "{{ route('admin.companies.addDepartment') }}",
    data: data,
    dataType: 'json',
    success: function(response) {
         $('#DepartmentList').html(response.view);
         $('#companyBranches').html(response.branchView);

        document.getElementById('d_name').value = "";

    //      departments = JSON.parse(document.getElementById('companyDepartments').value);
    //      console.log("departmentsdepartmentsdepartments")
    //      console.log(departments)

    //      for(i=0 ; i< branches.length ; i++){
    // var multiselect = document.getElementById('departments_'+branches[i].b_id);
    // var option = document.createElement("option");
    // var r = departments.length;
    //            option.text = departmentName;
    //            option.value = r;
    //            console.log(option)
    //            console.log("option")
    //            multiselect.add(option);
    // }

    },
    //new
    complete: function(){
        $('#LoadingModal').modal('hide');
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
    }
});

    }
    else {
      if(departmentName==""){

           document.getElementById('similarCompanyDepMessage').hidden = false;
         document.querySelector('#similarCompanyDepMessage span').textContent = '{{__("translate.Please fill out the field")}}';


      }
      else {
         document.getElementById('similarCompanyDepMessage').hidden = false;
         document.querySelector('#similarCompanyDepMessage span').textContent = '{{__("translate.There is department with the same name you entered")}}';
      }
    }











}



function deleteDepartment(i){
    if(departments.length!=0){

////////////to set departments for branches/////////////
if(branchesNumber>=1){
    //to show department area in each branch
    for(var c = 0; c < branchesNumber; c++){
        departmentArea = `departments_group${c+1}`;
        document.getElementById(departmentArea).hidden = false;
        departmentSelect = `departments${c+1}`;
        var multiselect = document.getElementById(departmentSelect);
        var option = document.createElement("option");
        option.value = i;
        multiselect.options.remove(option);
    }}}
    departments.splice(i, 1);
    x = "";
    for(i=0;i<departments.length;i++){
        x=x+ '<br> <div class="row"> <div class="col-md-8"> <div class="form-group"><input class="f1-last-name form-control" id="d_name"  value="'+departments[i]+'"> </div></div>'
        +'<div class="col-md-4" ><button class="btn btn-danger" onclick="deleteDepartment('+i+')"><i class="fa fa-trash"></i></button></div></div>'
    }
    $('#addedDepartment').html(x);


}

function departmentStep(){

        branchesNumber = document.getElementById('branchesNum').value;

        //to set department values to input and send it to the controller
        document.getElementById('departmentsList').value = JSON.stringify(departments);

        x="";

        //console.log("branchesNumberbranchesNumberbranchesNumberbranchesNumberbranchesNumberbranchesNumber");
        //console.log(branchesNumber);

        if(branchesNumber>1){

            document.getElementById('secondBranch').hidden = false;

            for(i=2;i<branchesNumber;i += 2){

                    x += `<div class="row">
                          <div class="col-md-6">
                        <div class="ribbon-wrapper card shadow-sm" style="border-radius: 5px;">
                          <div class="card-body">
                            <div class="ribbon ribbon-primary ribbon-right">الفرع ${i+1}</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone1_${i+1}">هاتف 1</label>
                                        <input class="f1-last-name form-control" id="phone1_${i+1}" type="text" name="phone1_${i+1}" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone2_${i+1}">هاتف 2</label>
                                        <input class="f1-last-name form-control" id="phone2_${i+1}" name="phone2_${i+1}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=address${i+1}">عنوان الفرع</label>
                                        <input class="f1-last-name form-control" id="address${i+1}" type="text" name="address${i+1}" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="departments_group${i+1}" hidden>
                                        <label for="departments${i+1}">أقسام الفرع</label>
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
                            <div class="ribbon ribbon-primary ribbon-right">الفرع ${i+2}</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone1_${i+2}">هاتف 1</label>
                                        <input class="f1-last-name form-control" id="phone1_${i+2}" type="text" name="phone1_${i+2}" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone2_${i+2}">هاتف 2</label>
                                        <input class="f1-last-name form-control" id="phone2_${i+2}" name="phone2_${i+2}" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=address${i+2}">عنوان الفرع</label>
                                        <input class="f1-last-name form-control" id="address${i+2}" type="text" name="address${i+2}" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="departments_group${i+2}" hidden>
                                        <label for="departments${i+2}">أقسام الفرع</label>
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

        ////////////////////////////////////////////////////////////////


    document.querySelector('#departmentStepButton').click();

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
