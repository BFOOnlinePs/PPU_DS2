@extends('layouts.app')
@section('title')
    {{__('translate.Main')}}{{--الرئيسية --}}
@endsection
@section('header_title')
    {{__('translate.Companies Management')}}{{--إدارة الشركات--}}
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a href="{{ route('admin.companies.index') }}">{{__('translate.Display Companies')}}{{--استعراض الشركات--}}</a>
@endsection

@section('content')

    <div>
        <button class="btn btn-primary  mb-2 btn-s" type="button" data-bs-toggle="modal" data-bs-target="#create_cities_modal"><span class="fa fa-plus"></span> اضافة مدينة</button>
    </div>

    <div class="card" style="padding-left:0px; padding-right:0px;">
        <input type="hidden" id="company_id">
        <div class="card-body" >
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>اسم المدينة بالعربية</th>
                                    <th>اسم المدينة بالانجليزية</th>
                                    <th>الوصف</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($data->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">لا توجد مدن</td>
                                    </tr>
                                @else
                                    @foreach($data as $key)
                                        <tr>
                                            <td>{{ $key->city_name_ar }}</td>
                                            <td>{{ $key->city_name_en }}</td>
                                            <td>{{ $key->city_description }}</td>
                                            <td>
                                                <button onclick="edit_cities_modal({{ $key }})" class="btn btn-success btn-sm"><span class="fa fa-edit"></span></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('project.admin.cities.modals.create_city_modal')
        @include('project.admin.cities.modals.edit_city_modal')
    </div>
@endsection
@section('script')
    <script>
        function edit_cities_modal(data){
            $('#city_id').val(data.id);
            $('#city_name_ar').val(data.city_name_ar);
            $('#city_name_en').val(data.city_name_en);
            $('#city_description').val(data.city_description);
            $('#edit_cities_modal').modal('show');
        }
    </script>
@endsection
