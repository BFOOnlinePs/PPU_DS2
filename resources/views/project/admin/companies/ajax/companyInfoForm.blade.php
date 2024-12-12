
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
                                <span>{{__("translate.There is company with the same name you entered,")}}{{--يوجد شركة بنفس الاسم الذي قمت بادخاله،--}}</span>
                                <u><a id="companyLink" style="color:#dc3545">{{__("translate.To move to the edit click here")}}{{--للانتقال إلى التعديل قم بالضغط هنا--}}</a></u>
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
                            <label for="f1-last-name">{{__('translate.Company Type')}}{{-- نوع الشركة --}}</label>

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
