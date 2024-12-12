@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{-- الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.Appearance Settings')}}{{--  اعدادات المظهر --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('admin.settings')}}">{{__('translate.Settings')}}{{-- الاعدادات  --}}</a>
@endsection
@section('style')
@endsection
@section('content')
<div class="row">
    <div class="card-body">
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">{{__('translate.Background Color')}}{{-- لون الخلفية --}}:</label>
                    <input class="form-control" type="text" id="primary_background_color" onchange="primary_background_color(this.value)" value="{{$background_color}}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">{{__('translate.Text Color')}}{{-- لون الخط --}}:</label>
                    <input class="form-control" type="color" id="primary_font_color" onchange="primary_font_color(this.value)" value="{{$text_color}}">
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
    <script>
        function primary_background_color(color_value)
        {
            $.ajax({
                url: "{{route('admin.color.primary_background_color')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'color_value' : color_value,
                },
                success: function(response) {

                },
                error: function() {

                }
            });
        }
        function primary_font_color(color_value)
        {
            $.ajax({
                url: "{{route('admin.color.primary_font_color')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'color_value' : color_value,
                },
                success: function(response) {

                },
                error: function() {

                }
            });
        }
    </script>
@endsection
