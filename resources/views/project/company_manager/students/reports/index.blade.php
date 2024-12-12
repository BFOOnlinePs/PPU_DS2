@extends('layouts.app')
@section('title')
{{__('translate.Student Report')}} {{-- تقرير الطالب --}}
@endsection
@section('header_title')
{{__('translate.Student Report')}} {{-- تقرير الطالب --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('company_manager.students.index')}}">{{__('translate.Students')}}{{-- الطلاب  --}}</a>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
                @include('project.company_manager.students.reports.includes.reportsList')
            </div>
        </div>
        @include('project.company_manager.students.reports.modals.noteModal')
        @include('project.company_manager.students.reports.modals.reportModal')
    </div>
@endsection
@section('script')
<script>
    function openReportModal(report_sr_id) {
        document.getElementById('report_sr_id').value = report_sr_id;
        $.ajax({
            beforeSend: function(){
            },
            url: "{{route('company_manager.students.reports.showReport')}}",
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                'report_sr_id' : report_sr_id,
            },
            success: function(response) {
                document.getElementById('sr_report_text').value = response.sr_report_text;
                if(response.sr_attached_file != null){
                    document.getElementById('sr_attached_file').href = `{{asset('public/storage/student_reports/${response.sr_attached_file}')}}`;
                    document.getElementById('sr_attached_file').style.display = '';
                }
            },
            complete: function(){

            },
            error: function(jqXHR) {

            }
        });
        $('#StudentReportModal').modal('show');
    }
    function openNoteModal(sr_id) {
        document.getElementById('note_sr_id').value = sr_id;
        $.ajax({
            beforeSend: function(){
            },
            url: "{{route('company_manager.students.reports.showNotes')}}",
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                'sr_id' : sr_id,
            },
            success: function(response) {
                document.getElementById('sr_notes_company').value = response.sr_notes_company;
            },
            complete: function(){

            },
            error: function(jqXHR) {

            }
        });
        $('#AddNoteModal').modal('show'); // Show the modal
    }
    let AddNoteForm = document.getElementById("addNoteForm");
    AddNoteForm.addEventListener("submit", (e) => {
        e.preventDefault();
        let sr_id = document.getElementById("note_sr_id").value;
        let sr_notes_company = document.getElementById("sr_notes_company").value;
        $.ajax({
            beforeSend: function(){
            },
            url: "{{route('company_manager.students.reports.addNotes')}}",
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                'sr_id' : sr_id,
                'sr_notes_company' : sr_notes_company
            },
            success: function(response) {
                $('#AddNoteModal').modal('hide');
            },
            complete: function(){

            },
            error: function(jqXHR) {

            }
        });
    });
</script>
@endsection

