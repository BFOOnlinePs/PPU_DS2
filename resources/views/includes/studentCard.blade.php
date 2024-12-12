<div class="row">
    <div class="col-md-6">
        <a href="{{route('students.personal_profile.index')}}">
        <div class="card income-card card-primary">
            <div class="card-body text-center">
                <div class="round-box">
                    <span class="fa fa-user mb-3" style="font-size: 50px;"></span>
                </div>
                <h5>{{__('translate.Profile')}}</h5>
                <div class="parrten">
                </div>
            </div>
        </div>
    </a>
</div>
    <div class="col-md-6">
        <a href="{{route('students.company.index')}}">
        <div class="card income-card card-primary">
            <div class="card-body text-center">
                <div class="round-box">
                    <span class="fa fa-building mb-3" style="font-size: 50px;"></span>
                </div>
                <h5>{{__('translate.Companies')}}</h5>
                <div class="parrten">
                </div>
            </div>
        </div>
    </a>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <a href="{{route('students.attendance.index')}}">
            <div class="card income-card card-primary">
                <div class="card-body text-center">
                    <div class="round-box">
                        <span class="fa fa-check mb-3" style="font-size: 50px;"></span>
                    </div>
                    <h5>{{__('translate.Attendance Logs')}}</h5>
                    <div class="parrten">
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{route('students.payments.index')}}">
            <div class="card income-card card-primary">
                <div class="card-body text-center">
                    <div class="round-box">
                        <span class="fa fa-dollar  mb-3" style="font-size: 50px;"></span>
                    </div>
                    <h5>{{__('translate.Payments')}}</h5>
                    <div class="parrten">
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
