<div class="card">
    <input type="hidden" value="{{$user->u_id}}" id="u_id">
    <div class="card-header pb-0">
        <a href="{{route('admin.users.edit' , ['id'=>$user->u_id])}}" class="fa fa-edit" style="font-size: x-large;"><span></span></a>
        <h6 class="card-title mb-0"> {{__('translate.Main Information')}} {{-- المعلومات الأساسية --}}</h6>
        <div class="card-options">
            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            <a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
        </div>
    </div>
    <div class="card-body">
        <form>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="profile-title">
                        <div class="text-center">
                            <img class="img-70 rounded-circle" alt="" src="https://laravel.pixelstrap.com/viho/assets/images/dashboard/1.png">
                            <div class="media-body">
                                <h3 class="mb-1 f-20 txt-primary">{{$user->name}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">{{__('translate.Username')}} {{-- اسم المستخدم --}}</label>
                        <input class="form-control" value="{{$user->u_username}}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{__('translate.Phone Number')}} {{-- رقم الجوال --}}</label>
                        <input class="form-control" value="{{$user->u_phone1}}" readonly>
                    </div>
                    <!-- Add other inputs for the second column -->
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">{{__('translate.Email')}} {{-- البريد الإلكتروني --}}</label>
                        <input class="form-control" type="text" value="{{$user->email}}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> {{__('translate.Alternative Phone Number')}} {{-- رقم الجوال الاحتياط --}}</label>
                        <input class="form-control" value="{{$user->u_phone2}}" readonly>
                    </div>
                    <!-- Add other inputs for the third column -->
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">{{__('translate.Birth Date')}} {{-- تاريخ الميلاد --}}</label>
                        <input class="form-control" value="{{$user->u_date_of_birth}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">{{__('translate.Residential Address')}} {{-- عنوان السكن --}}</label>
                        <input class="form-control" value="{{$user->u_address}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">{{__('translate.tawjihi_rate')}} {{-- عنوان السكن --}}</label>
                        <input class="form-control" value="{{$user->u_tawjihi_gpa}}" readonly>
                    </div>
                </div>
                <!-- Add other rows or inputs as needed -->
            </div>
            <div class="form-footer">
                <!-- ... -->
            </div>
        </form>
    </div>
</div>
