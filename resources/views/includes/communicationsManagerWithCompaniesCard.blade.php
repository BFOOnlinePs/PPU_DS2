<div class="row">
    <div class="col-md-6">
        <a href="{{route('admin.companies.index') }}">
        <div class="card income-card card-primary">
            <div class="card-body text-center">
                <div class="round-box">
                    <span class="fa fa-building mb-3" style="font-size: 50px;"></span>
                </div>
                <h5>{{__('translate.Companies')}}{{-- الشركات --}}</h5>
                <div class="parrten">
                </div>
            </div>
        </div>
    </a>
</div>
    <div class="col-md-6">
        <a href="{{route('communications_manager_with_companies.students.index')}}">
        <div class="card income-card card-primary">
            <div class="card-body text-center">
                <div class="round-box">
                    <span class="fa fa-users mb-3" style="font-size: 50px;"></span>
                </div>
                <h5>{{__('translate.Students')}}{{-- الطلاب --}}</h5>
                <div class="parrten">
                </div>
            </div>
        </div>
    </a>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <a href="{{route('communications_manager_with_companies.companies.index')}}">
            <div class="card income-card card-primary">
                <div class="card-body text-center">
                    <div class="round-box">
                        <span class="fa fa-map-marker mb-3" style="font-size: 50px;"></span>
                    </div>
                    <h5>{{__('translate.Training Places')}}{{-- أماكن التدريب --}}</h5>
                    <div class="parrten">
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
