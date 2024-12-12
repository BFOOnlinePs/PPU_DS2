<table class="table-bordered table-striped table-hover" style="font-size: x-small ; width: 100%">
    <thead class="text-center">
    <tr class="bg-primary">
        <th scope="col" style="display:none;">id</th>
        <th scope="col">{{__('translate.Company Name')}} {{-- اسم الشركة --}}</th>
        <th scope="col">{{__('translate.company_trainees')}}</th>
        <th scope="col">{{__('translate.candidate_students')}}</th>
        <th scope="col">{{__('translate.capacity')}}</th>
        <th scope="col" style="width: 200px">{{__('translate.company_status')}}</th>
        <th scope="col" style="width: 200px">{{__('translate.Operations')}} {{--  العمليات --}}</th>
    </tr>
    </thead>
    <tbody>
    @if ($data->isEmpty())
        <tr>
            <td colspan="6" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
        </tr>
    @else
        @foreach ($data as $key)
            <tr>
                <td style="display:none;">{{ $key->c_id }}</td>
                <td>
                    <span onclick="company_modal({{ $key }})">
                        @if(app()->isLocale('en') || (app()->isLocale('ar') && empty($key->c_name)))
                            {{ $key->c_english_name }}
                        @elseif(app()->isLocale('ar') || (app()->isLocale('en') && empty($key->c_english_name)))
                            {{ $key->c_name }}
                        @endif
                    </span>
                </td>
                <td>
                    @foreach($key->student_company as $item)
                        <a href="{{ route('admin.users.details',['id'=>$item->u_id]) }}">{{ $item->name }}</a> ,
                    @endforeach
                </td>
                {{--                                                        @if (auth()->user()->u_role_id == 1)--}}
                {{--                                                            <td><a href="{{route('admin.users.details',['id'=>$key->manager->u_id])}}">{{$key->manager->name}}</a></td>--}}
                {{--                                                        @else--}}
                {{--                                                            <td>{{$key->manager->name}}</td>--}}
                {{--                                                        @endif--}}

                {{-- <td><a href="{{route('admin.companies_categories.index')}}">{{$key->companyCategories->cc_name}}</a></td> --}}
                {{--                                                        @if($key->companyCategories != null)--}}
                {{--                                                            <td><a href="{{route("admin.companies_categories.index")}}">{{$key->companyCategories->cc_name}}</a></td>--}}
                {{--                                                        @else--}}
                {{--                                                            <td>{{__('translate.Unspecified')}}--}}{{--غير محدد--}}{{--</td>--}}
                {{--                                                        @endif--}}

                <td>
                    @foreach($key->student_company_nomination as $item)
                        <a href="{{ route('admin.users.details',['id'=>$item->u_id]) }}">{{ $item->name }}</a> ,
                    @endforeach
                </td>

                <td class="text-center">
                    <input type="text" onchange="update_capacity_ajax({{ $key->c_id }},this.value)" class="" style="width: 30px ; text-align: center; " value="{{ $key->c_capacity }}" placeholder=""> / {{ $key->student_company_count }}
                </td>
                <td class="text-center">
                    <label class="switch">
                        <div class="media-body text-center switch-sm icon-state">
                            <label class="switch">
                                <input onchange="update_company_status({{ $key->c_id }},this.checked)" @if($key->c_status == 1) checked="" @endif  type="checkbox"><span class="switch-state"></span>
                            </label>
                        </div>
{{--                        <input style="font-size: 5px"  type="checkbox" onchange="update_company_status({{ $key->c_id }},this.checked)" @if($key->c_status == 1) checked="" @endif><span class="switch-state"></span>--}}
                    </label>
                </td>
                <td class="">
                    <a class="" onclick='location.href="{{route("admin.companies.edit",["id"=>$key->c_id])}}"'><u>{{__('translate.display')}}</u></a>
                    |<a class="" data-container="body" onclick='show_student_nomination_modal({{ $key }})'><u>{{__('translate.nominate_students')}}</u></a>
                    |<a class="" data-container="body" onclick='addAttachmentModal({{ $key->c_id }})'><u class="">{{__('translate.agreements')}}</u></a>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
