@extends('layouts.app')
@section('title')
    {{__('translate.files')}}{{-- الرئيسية --}}
@endsection
@section('header_title')
    {{__('translate.files')}}{{-- الرئيسية --}}
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('style')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <button data-bs-toggle="modal" data-bs-target="#add_attachment_modal" class="btn btn-primary">{{ __('translate.add_file') }}</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>{{ __('translate.file') }}</th>
                                            <th>{{ __('translate.Notes') }}</th>
                                            <th>
                                                {{ __('translate.Operations') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($data->isEmpty())
                                            <tr>
                                                <td colspan="3" class="text-center">لا توجد بيانات</td>
                                            </tr>
                                        @else
                                            @foreach($data as $key)
                                                <tr>
                                                    <td>
                                                        <a href="{{ asset('public/storage/files/'. $key->mea_file ) }}" target="_blank">{{ $key->mea_file }}</a>
                                                    </td>
                                                    <td>{{ $key->mea_description }}</td>
                                                    <td>
                                                        <button onclick="add_version_model({{ $key }})" class="btn btn-sm btn-success"><span class="fa fa-file"></span></button>
{{--                                                        <button onclick="list_versions_modal({{ $key }},{{ $key->versions }})" class="btn btn-sm btn-dark"><span class="fa fa-search"></span></button>--}}
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
            </div>
        </div>
        @include('project.monitor_evaluation.files.modals.add_attchment_model')
        @include('project.monitor_evaluation.files.modals.add_version_model')
        @include('project.monitor_evaluation.files.modals.list_versions_modal')
    </div>
@endsection
@section('script')
    <script>
        function add_version_model(parent) {
            $('#add_version_model').modal('show');
            $('#mea_attachment_id').val(parent.mea_attachment_id);
            var file_table = $('#file_table');
            file_table.empty();
            if (parent.versions.length === 0) {
                src = '{{ asset('public/storage/files') }}' + '/' + parent.mea_file;
                file_table.append(`
            <tr>
                <td>
                    <a target="_blank" href="${src}">${src}</a>
                </td>
                <td>${parent.mea_description}</td>
                <td>${parent.created_at}</td>
            </tr>
    `);
            } else {
                parent_src = '{{ asset('public/storage/files') }}' + '/' + parent.mea_file;
                $.each(parent.versions, function(index, value) {
                    src = '{{ asset('public/storage/files') }}' + '/' + value.mea_file;
                    file_table.append(`
            <tr>
                <td>
                    <a target="_blank" href="${parent_src}">${parent_src}</a>
                </td>
                <td>${parent.mea_description}</td>
                <td>${parent.created_at}</td>
            </tr>
            <tr>
                <td>
                    <a target="_blank" href="${src}">${src}</a>
                </td>
                <td>${value.mea_description}</td>
                <td>${value.created_at}</td>
            </tr>
        `);
                });
            }
        }
        function list_versions_modal(parent,versions) {
            $('#list_versions_modal').modal('show');
            var file_table = $('#file_table');
            file_table.empty();
            if (versions.length === 0) {
                src = '{{ asset('public/storage/files') }}' + '/' + parent.mea_file;
                file_table.append(`
            <tr>
                <td>
                    <a target="_blank" href="${src}">${src}</a>
                </td>
                <td>${parent.mea_description}</td>
                <td>${parent.created_at}</td>
            </tr>
    `);
            } else {
                parent_src = '{{ asset('public.storage/files') }}' + '/' + parent.mea_file;
                $.each(versions, function(index, value) {
                    src = '{{ asset('public/storage/files') }}' + '/' + value.mea_file;
                    file_table.append(`
            <tr>
                <td>
                    <a target="_blank" href="${parent_src}">${parent_src}</a>
                </td>
                <td>${parent.mea_description}</td>
                <td>${parent.created_at}</td>
            </tr>
            <tr>
                <td>
                    <a target="_blank" href="${src}">${src}</a>
                </td>
                <td>${value.mea_description}</td>
                <td>${value.created_at}</td>
            </tr>
        `);
                });
            }
        }
    </script>
@endsection
