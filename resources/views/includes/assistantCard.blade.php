<div class="row">
    <div class="col-md-6">
        <a href="{{route('supervisor_assistants.majors.index' , ['id' => auth()->user()->u_id])}}">
        <div class="card income-card card-primary">
            <div class="card-body text-center">
                <div class="round-box">
                    <span class="fa fa-book mb-3" style="font-size: 50px;"></span>
                </div>
                <h5>{{__('translate.Majors')}}{{-- التخصصات --}}</h5>
                <div class="parrten">
                </div>
            </div>
        </div>
    </a>
</div>
    <div class="col-md-6">
        <a href="{{route('supervisor_assistants.students.index' , ['ms_major_id' => null]) }}">
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
        <a href="{{route('supervisor_assistants.companies.index')}}">
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
    <div class="col-md-6">
        <a href="{{ route('admin.companies.index')}}">
            <div class="card income-card card-primary">
                <div class="card-body text-center">
                    <div class="round-box">
                        <span class="fa fa-building mb-3" style="font-size: 50px;"></span>
                    </div>
                    <h5>{{__('translate.Companies')}}{{-- أماكن التدريب --}}</h5>
                    <div class="parrten">
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
