

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->isLocale('en') ? 'ltr' : 'rtl' }}">
    <head>
        <title>{{ $title }}</title>
       <style>
         @page {
                    header: page-header;
                    footer: page-footer;
                    background: url("{{asset('assets/images/report-background.jpg')}}") no-repeat 0 0;
                    height: 100vh;
            }
       </style>
    <style>

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }
        .td {
            border: 1px solid black;
            padding: 8px;
            /* text-align: left; */
        }
        .container {
            display: flex;
        }

        .section {
            flex: 1;
            border: 1px solid #ccc;
            box-sizing: border-box;
            padding: 10px;
        }

    </style>
</head>
<body>
      <table class="table">

        <tbody>
              <tr style="width:100%">

                <td style="width:1%;">
                    {{-- <img src="{{asset('assets/images/ppu.png')}}" alt="" width="60px" height="65px"> --}}
                </td>
                <td style="width:20%; text-align: right; font-size: 15px;">
                    {{-- {{__('translate.Palestine Polytechnic University')}} --}}
                <br>
                    {{-- {{__('translate.Dual Studies College')}} --}}
                </td>
                {{-- <td style="width:50%; text-align: center; font-weight: bold;">{{$title}}</td> --}}
                {{-- <td style="width:59%; text-align: center; font-weight: bold;">تقرير الفصل الإجمالي</td> --}}
                <td style="width:59%; text-align: center; font-size: 20px;">{{$title}}</td>
                <td style="width:21%; text-align: right; font-size: 14px;">
                    {{__('translate.report_date')}}{{--تاريخ التقرير--}} {{now()->format('Y-m-d')}}
                    <br>
                    {{-- {{$semester}} --}}
                    @if ($semester == 1)
                        <span>{{__('translate.First Semester')}}{{--الفصل الدراسي الأول--}},  {{$year}}</span>
                    @elseif ($semester == 2)
                        <span>{{__('translate.Second Semester')}}{{--الفصل الدراسي الثاني--}},  {{$year}}</span>
                    @elseif ($semester == 3)
                        <span>{{__('translate.Summer Semester')}}{{--الفصل الدراسي الصيفي--}},  {{$year}}</span>
                        @else
                        <span>{{__('translate.All Semesters')}}{{--جميع الفصول--}},  {{$year}}</span>
                    @endif
                </td>
                {{-- <td><button class="btn btn-primary"> استعراض</button></td> --}}
              </tr>
        </tbody>
      </table>


    {{-- <hr> --}}
    <br>
    <br>
    <div style="font-size: 15px;">
    <br>

        {{-- <h3>
            {{$title}}
        </h3> --}}

        {{-- <br> --}}
    <div style="width:60%; margin-left: auto; margin-right: auto;">
        <table class="table">
            <tbody>
                <tr style="background-color: rgba(185, 178, 178, 0.188)">
                    <td class="td"><b>{{__('translate.Company Type')}}{{--نوع الشركة--}}</b></td>
                    <td class="td">
                      @if ($companyType == 1)
                          <span>{{__('translate.Public Sector')}}{{--عام--}}</span>
                      @elseif ($companyType == 2)
                          <span>{{__('translate.Private Sector')}}{{--خاص--}}</span>
                      @else
                          <span>{{$companyType}}</span>
                      @endif
                  </td>
                  <td class="td"><b>{{__('translate.Company Category')}}{{--تصنيف الشركة--}}</b></td>
                  <td class="td">{{$companyCateg}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <br>
    <div>
        <div>
            <table class="table">
                <thead>
                    <tr  class="td">
                        <th class="td" scope="col">{{__('translate.Company Name')}} {{-- اسم الشركة --}}</th>
                        <th class="td" scope="col">{{__('translate.Company Manager')}}{{-- مدير الشركة --}}</th>
                        <th class="td" scope="col">{{__('translate.Company Category')}}{{-- تصنيف الشركة --}}</th>
                        <th class="td" scope="col">{{__('translate.Company Type')}}{{-- نوع الشركة --}}</th>
                        <th class="td" scope="col">{{__('translate.Total Students')}}{{-- إجمالي الطلاب--}} </th>
                    </tr>
                </thead>
                <tbody>
                @if ($data->isEmpty())
                    <tr>
                    <td  class="td" colspan="3"><span>{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                    </tr>
                @else
                    @foreach ($data as $key)
                    <tr>
                        <td class="td">{{ $key->c_name }}</td>
                        <td class="td">{{ $key->manager->name }}</td>
                        <td class="td">{{ $key->companyCategories->cc_name}}</td>
                        @if( $key->c_type == 1) <td class="td">{{__('translate.Public Sector')}}{{-- قطاع عام --}}</td>@endif
                        @if( $key->c_type == 2) <td class="td">{{__('translate.Private Sector')}}{{-- قطاع خاص --}}</td>@endif
                        <td class="td">
                          {{$key->studentsTotal}}
                        </td>
                    </tr>
                    @endforeach
                @endif
                </tbody>

            </table>
        </div>

        {{-- {{$PAGENO}} --}}
    </div>

    <htmlpagefooter name="page-footer">
        {{-- <hr> --}}
        {{-- <div style="display: block;text-align:center; padding: 30px !important;">Page {PAGENO} of {nbpg}</div> --}}
        <div style="display: block;text-align:center; padding: 30px !important;">{{__('translate.page')}}{{--صفحة--}} {PAGENO} {{__('translate.from')}}{{--من--}} {nbpg}</div>
    </htmlpagefooter>


    {{-- <footer>
        hi
    </footer> --}}
</div>
</body>

</html>
{{-- @endsection --}}



