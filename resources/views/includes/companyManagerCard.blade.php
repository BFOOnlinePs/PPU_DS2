<div class="row">
    <div class="col-md-6">
        <a href="{{route('company_manager.students.index')}}">
        <div class="card income-card card-primary">
            <div class="card-body text-center">
                <div class="round-box">
                    <span class="fa fa-users mb-3" style="font-size: 50px;"></span>
                </div>
                <h5>{{__('translate.Students')}}</h5>
                <div class="parrten">
                </div>
            </div>
        </div>
    </a>
</div>
    <div class="col-md-6">
        <a href="{{route('company_manager.records.index')}}">
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
</div>
<div class="row">
    <div class="col-md-6">
        <a href="{{route('company_manager.payments.index')}}">
            <div class="card income-card card-primary">
                <div class="card-body text-center">
                    <div class="round-box">
                        <span class="fa fa-dollar mb-3" style="font-size: 50px;"></span>
                    </div>
                    <h5>{{__('translate.Payments')}}</h5>
                    <div class="parrten">
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
