@if (!empty($student_report))
    <div class="modal fade show" id="EditStudentReportModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border: none;">
                <div class="modal-header" style="height: 73px;">
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row p-3 m-5">
                            <div class="col-md-4 text-center" >
                                <h1><span class="fa fa-file-text" style="text-align: center; font-size:80px; "></span></h1>
                                <h3>{{__('translate.Display Student Report')}}{{-- عرض تقرير الطالب --}}</h3>
                                <hr>
                                <p>{{__("translate.In this section, you can display student's report")}}{{-- في هذا القسم يمكنك عرض تقرير الطالب --}}</p>
                            </div>
                            <div class="col-md-8">
                                <form class="form-horizontal" id="editStudentReportForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('post')
                                    <div class="row">
                                            <div class="col-md-6">
                                                <label>{{__('translate.Report')}}{{-- التقرير --}}</label>
                                                <textarea name="" id="" cols="100" rows="5" readonly>{{$student_report->sr_report_text}}</textarea>
                                                @if (!empty($student_report->sr_attached_file))
                                                    <a href="{{ asset('public/storage/student_reports/'.$student_report->sr_attached_file) }}" type="button" download>{{__('translate.Download Report Attachment')}}{{-- تنزيل الملف المرفق مع التقرير --}}</a>
                                                @endif
                                                <br>
                                                @if (auth()->user()->u_role_id == 3)
                                                    <label>{{__('translate.Supervisor Notes')}}{{--ملاحظات المشرف--}}</label>
                                                    <textarea cols="100" rows="5" id="sr_notes">{{$student_report->sr_notes}}</textarea>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                    @if (auth()->user()->u_role_id == 3)
                        <div class="modal-footer ">
                            <button type="submit" class="btn btn-primary" onclick="submit_notes_supervisor({{$student_report->sr_id}})">{{__("translate.Send Supervisor's Notes")}}{{--إرسال ملاحظات المشرف--}</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endif
