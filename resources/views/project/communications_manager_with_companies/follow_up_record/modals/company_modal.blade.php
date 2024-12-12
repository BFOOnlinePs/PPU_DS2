<div class="modal fade show" id="company_modal" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="pull-left" id="company_name">Tab With Icon</h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="company_id">
                        <div class="tabbed-card">
                            <ul class="pull-right nav nav-pills nav-primary" id="pills-clrtab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-clrhome-tab" data-bs-toggle="pill" href="#pills-clrhome" role="tab" aria-controls="pills-clrhome" aria-selected="false"><i class="icofont icofont-ui-home"></i>{{ __('translate.Company Information') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a onclick="list_contact_company()" class="nav-link" id="pills-clrprofile-tab" data-bs-toggle="pill" href="#pills-clrprofile" role="tab" aria-controls="pills-clrprofile" aria-selected="false">
                                        <i class="icofont icofont-man-in-glasses"></i>{{ __('translate.contact_information') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a onclick="list_branches()" class="nav-link" id="pills-clrcontact-tab" data-bs-toggle="pill" href="#pills-clrcontact" role="tab" aria-controls="pills-clrcontact" aria-selected="true">
                                        <i class="icofont icofont-contacts"></i>{{ __('translate.branches') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a onclick="list_student_company_ajax()" class="nav-link" id="student-tab" data-bs-toggle="pill" href="#student" role="tab" aria-controls="student" aria-selected="true">
                                        <i class="icofont icofont-contacts"></i>{{ __('translate.Students') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a onclick="payment_table_ajax()" class="nav-link" id="payment-tab" data-bs-toggle="pill" href="#payment" role="tab" aria-controls="payment" aria-selected="true">
                                        <i class="icofont icofont-contacts"></i>{{ __('translate.Payments') }}
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-clrtabContent">
                                <div class="tab-pane fade active show" id="pills-clrhome" role="tabpanel" aria-labelledby="pills-clrhome-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('translate.company_arabic_name') }}</label>
                                                <input type="text" onchange="update_company_information('c_name',this.value)" id="company_arabic_name" class="form-control" placeholder="اسم الشركة بالعربي">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('translate.company_english_name') }}</label>
                                                <input type="text" onchange="update_company_information('c_english_name',this.value)" id="company_english_name" class="form-control" placeholder="اسم الشركة بالعربي">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('translate.Company Phone Number') }}</label>
                                                <input type="text" onchange="update_company_information('b_phone1',this.value)" id="company_phone" class="form-control" placeholder="رقم هاتف الشركة">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('translate.Company Address') }}</label>
                                                <input type="text" id="company_address" onchange="update_company_information('b_address',this.value)" class="form-control" placeholder="{{ __('translate.Company Address') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('translate.Company Type') }}</label>
                                                <select onchange="update_company_information('c_type', this.value )" class="form-control" name="" id="company_type">
                                                    <option value="1">{{ __('translate.Public Sector') }}</option>
                                                    <option value="2">{{ __('translate.Private Sector') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('translate.Company Category') }}</label>
                                                <select onchange="update_company_information('c_category_id',this.value)" class="form-control" name="" id="company_category_id">
                                                    @foreach($company_category as $key)
                                                        <option value="{{ $key->cc_id }}">{{ $key->cc_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">{{ __('translate.Website') }}</label>
                                                <input onchange="update_company_information('c_website',this.value)" type="text" id="company_website" class="form-control" placeholder="{{ __('translate.Website') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">{{ __('translate.Company Description') }}</label>
                                                <textarea name="" onchange="update_company_information('c_description',this.value)" id="company_description" class="form-control" cols="30" rows="2" placeholder="{{ __('translate.Company Description') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-clrprofile" role="tabpanel" aria-labelledby="pills-clrprofile-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button onclick="add_company_contact_modal()" class="btn btn-primary btn-sm mb-2">{{ __('translate.add_a_person') }}</button>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <div id="list_contact_company">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-clrcontact" role="tabpanel" aria-labelledby="pills-clrcontact-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-sm btn-primary mb-2" onclick="add_branches_modal()">{{ __('translate.Add Branch') }}</button>
                                            <div id="list_branches">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="student" role="tabpanel" aria-labelledby="student-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <div id="list_student_company">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                    <div id="payment_table">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Close')}}{{-- إغلاق --}}</button>
            </div>
        </div>
    </div>
</div>
