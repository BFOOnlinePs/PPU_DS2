@extends('layouts.app')
@section('title')
{{__('translate.Training Places')}}{{-- أماكن التدريب --}}
@endsection
@section('header_title')
{{__('translate.Training Places')}}{{-- أماكن التدريب --}}
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('communications_manager_with_companies.companies.index') }}">{{__('translate.Training Places')}}{{-- أماكن التدريب --}}</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">{{ __('translate.select_company') }}</label>
                                <select onchange="communications_manager_with_companies_table_ajax()" name="" id="company_id" class="form-control">
                                    <option value="">{{ __('translate.All Companies') }}</option>
                                    @foreach($company as $key)
                                        <option value="{{ $key->c_id }}">{{ $key->c_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="">{{ __('translate.company_status') }}</label>
                                <select onchange="communications_manager_with_companies_table_ajax()" name="" id="company_status" class="form-control">
                                    <option value="">{{ __('translate.all') }}</option>
                                    <option value="1">مفعلة</option>
                                    <option value="0">غير مفعلة</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="">{{ __('translate.companies_have_absorptive_capacity') }}</label>
                                <select onchange="communications_manager_with_companies_table_ajax()" name="" id="capacity" class="form-control">
                                    <option value="">{{ __('translate.all') }}</option>
                                    <option value="1">نعم</option>
                                    <option value="0">لا</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="">متدربين</label>
                                <select onchange="communications_manager_with_companies_table_ajax()" name="" id="trainees" class="form-control">
                                    <option value="yes_trainees">شركات يوجد لها متدربين</option>
                                    <option value="no_trainees">شركات لا يوجد لها متدربين</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div id="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <div id="communications_manager_with_companies_table_ajax">

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--                @include('project.communications_manager_with_companies.companies.tables.companiesList')--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            communications_manager_with_companies_table_ajax();
        });
        function communications_manager_with_companies_table_ajax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route('communications_manager_with_companies.companies.communications_manager_with_companies_table_ajax') }}',
                type: 'POST',
                data: {
                    'company_id' : $('#company_id').val(),
                    'company_status' : $('#company_status').val(),
                    'capacity' : $('#capacity').val(),
                    'trainees' : $('#trainees').val(),
                },
                success: function(response) {
                    if(response.success === 'true'){
                        $('#communications_manager_with_companies_table_ajax').html(response.view);
                    }
                },
                error: function(xhr) {

                }
            });
        }
    </script>
@endsection
