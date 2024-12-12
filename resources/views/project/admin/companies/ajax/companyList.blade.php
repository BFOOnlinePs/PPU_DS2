<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col" style="display:none;">id</th>
                <th scope="col">{{__('translate.Company Name')}} {{-- اسم الشركة --}}</th>
                <th scope="col">{{__('translate.Company Manager')}}{{-- مدير الشركة --}}</th>
                <th scope="col">{{__('translate.Company Category')}}{{-- تصنيف الشركة --}}</th>
{{--                <th scope="col">{{__('translate.Company Type')}}--}}{{-- نوع الشركة --}}{{--</th>--}}
                <th scope="col">الطاقة الاستيعابية</th>
                <th scope="col">حالة الشركة</th>
                <th scope="col" style="width: 200px">{{__('translate.Operations')}} {{--  العمليات --}}</th>
            </tr>
        </thead>
        <tbody>
        @if ($data->isEmpty())
            <tr>
                <td colspan="7" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
            </tr>
        @else
            @foreach ($data as $key)
                <tr>
                    <td style="display:none;">{{ $key->c_id }}</td>
                    <td>
                        <a href="{{route('admin.users.details',['id'=>$key->manager->u_id])}}">
                            @if(app()->isLocale('en') || (app()->isLocale('ar') && empty($key->c_name)))
                                {{ $key->c_english_name }}
                            @elseif(app()->isLocale('ar') || (app()->isLocale('en') && empty($key->c_english_name)))
                                {{ $key->c_name }}
                            @endif
                        </a>
                    </td>
                    @if (auth()->user()->u_role_id == 1)
                        <td><a href="{{route('admin.users.details',['id'=>$key->manager->u_id])}}">{{$key->manager->name}}</a></td>
                    @else
                        <td>{{$key->manager->name}}</td>
                    @endif

                    {{-- <td><a href="{{route('admin.companies_categories.index')}}">{{$key->companyCategories->cc_name}}</a></td> --}}
                    @if($key->companyCategories != null)
                        <td><a href="{{route("admin.companies_categories.index")}}">{{$key->companyCategories->cc_name}}</a></td>
                    @else
                        <td>{{__('translate.Unspecified')}}{{--غير محدد--}}</td>
                    @endif
                    @if( $key->c_type == 1) <td>{{__('translate.Public Sector')}}{{-- قطاع عام --}}</td>@endif
                    @if( $key->c_type == 2) <td>{{__('translate.Private Sector')}}{{-- قطاع خاص --}}</td>@endif
                    <td>
                        <input type="text" onchange="update_capacity_ajax({{ $key->c_id }},this.value)" class="form-control" value="{{ $key->c_capacity }}" placeholder="">
                    </td>
                    <td>
                        <label class="switch">
                            <input onchange="update_company_status({{ $key->c_id }},this.checked)" type="checkbox" @if($key->c_status == 1) checked="" @endif><span class="switch-state"></span>
                        </label>
                    </td>
                    <td>
                          <button class="btn btn-dark btn-xs" onclick='location.href="{{route("admin.companies.edit",["id"=>$key->c_id])}}"'><i class="fa fa-search"></i></button>
                          <button class="btn btn-dark btn-xs" onclick='show_student_nomination_modal({{ $key }})'>اقتراح طلاب</button>
                        <button class="btn btn-dark btn-xs" data-container="body" onclick='addAttachmentModal({{ $key->c_id }})'><i class="fa fa-file"></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    </table>
</div>
