<div class="row">
    <div class="col-md-6">
        <a href="{{route('home')}}">
        <div class="card income-card card-primary">
            <div class="card-body text-center">
                <div class="round-box">
                    <span class="fa fa-home mb-3" style="font-size: 50px;"></span>
                </div>
                <h5>{{__('translate.Main')}}{{-- الرئيسية --}}</h5>
                <div class="parrten">
                </div>
            </div>
        </div>
    </a>
    </div>
    <div class="col-md-6">
        <a href="{{route('monitor_evaluation.semesterReport')}}">
            <div class="card income-card card-primary">
                <div class="card-body text-center">
                    <div class="round-box">
                        <span class="fa fa-file-text mb-3" style="font-size: 50px;"></span>
                    </div>
                    <h5>تقارير</h5>
                    <div class="parrten">
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <a href="{{route('monitor_evaluation.companiesReport')}}">
            <div class="card income-card card-primary">
                <div class="card-body text-center">
                    <div class="round-box">
                        <span class="fa fa-file-pdf-o mb-3" style="font-size: 50px;"></span>
                    </div>
                    <h5>{{__("translate.Companies' Report")}}{{-- تقرير الشركات --}}</h5>
                    <div class="parrten">
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{route('monitor_evaluation.companiesPaymentsReport')}}">
            <div class="card income-card card-primary">
                <div class="card-body text-center">
                    <div class="round-box">
                        <span class="fa fa-file mb-3" style="font-size: 50px;"></span>
                    </div>
                    <h5>{{__("translate.Companies Payments' Report")}}{{-- تقرير دفعات الشركات --}}</h5>
                    <div class="parrten">
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <a href="{{ route('monitor_evaluation.paymentsReport')}}">
            <div class="card income-card card-primary">
                <div class="card-body text-center">
                    <div class="round-box">
                        <span class="fa fa-file-o mb-3" style="font-size: 50px;"></span>
                    </div>
                    <h5>{{__("translate.Payments' Report")}}{{-- تقرير الدفعات المالية --}}</h5>
                    <div class="parrten">
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
