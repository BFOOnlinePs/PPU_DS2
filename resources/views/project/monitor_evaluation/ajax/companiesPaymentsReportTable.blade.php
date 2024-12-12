<div id="companiesPaymentsReportTable">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" style="display:none;">id</th>
                    <th scope="col">{{__('translate.Company')}} {{-- الشركة --}}</th>
                    <th scope="col">{{__('translate.The Trainee')}} {{-- المتدرب --}}</th>
                    <th scope="col">{{__('translate.Payment Total')}} {{--إجمالي الدفعات --}}</th>
                    <th scope="col">{{__('translate.Total of Confirmed Payments')}} {{--  إجمالي الدفعات المؤكد عليها  --}}</th>
                    <th scope="col">{{__('translate.Payments Details')}} {{-- تفاصيل الدفعات  --}}</th>

                </tr>
            </thead>
            <tbody>
            @if ($companiesPayments->isEmpty())
                <tr>
                    <td colspan="6" class="text-center"><span>{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                </tr>
            @else
                @foreach ($companiesPayments as $key)
                    <tr>
                        <td style="display:none;">{{ $key->p_id }}</td>
                        <td>{{ $key->payments->c_name }}</td>
                        <td>{{ $key->userStudent->name }}</td>
                        <td>
                            @foreach ($key->paymentsTotalCollection as $item)
                                @if($item["total"] != 0)
                                    <span class="badge rounded-pill badge-danger">{{ $item["total"] == intval($item["total"]) ? number_format($item["total"]) : number_format(floor($item["total"] * 100) / 100, 2, '.', '') }} <span>{{$item["symbol"]}}</span></span>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($key->approvedPaymentsTotalCollection as $item)
                            @if($item["total"] != 0)
                                <span class="badge rounded-pill badge-danger">{{ $item["total"] == intval($item["total"]) ? number_format($item["total"]) : number_format(floor($item["total"] * 100) / 100, 2, '.', '') }} <span>{{$item["symbol"]}}</span></span>
                            @endif
                            @endforeach
                        </td>



                        <td>
                            <form id="testForm" action="{{route('monitor_evaluation.companyPaymentDetailes')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input hidden id="test" name="test" value="{{base64_encode(serialize($key))}}">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                            </form>
                          </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            @if (!$companiesPayments->isEmpty())
            <tfoot>
                <tr>
                    <td colspan="2">{{__('translate.total_companies_payments')}}{{--إجمالي دفعات الشركات--}}</td>
                    <td>
                        @foreach ($totalCollection as $item)
                            @if($item["total"] != 0)
                                <span class="badge rounded-pill badge-danger">{{ $item["total"] == intval($item["total"]) ? number_format($item["total"]) : number_format(floor($item["total"] * 100) / 100, 2, '.', '') }} <span>{{$item["symbol"]}}</span></span>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($totalApprovedCollection as $item)
                            @if($item["total"] != 0)
                                <span class="badge rounded-pill badge-danger">{{ $item["total"] == intval($item["total"]) ? number_format($item["total"]) : number_format(floor($item["total"] * 100) / 100, 2, '.', '') }} <span>{{$item["symbol"]}}</span></span>
                            @endif
                        @endforeach
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
