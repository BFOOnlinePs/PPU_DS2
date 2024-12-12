
<div class="modal fade show" id="StudentReportModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                            <form class="form-horizontal" id="StudentReportForm" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" name="report_sr_id" id="report_sr_id">
                                            <label>{{__('translate.Report')}}{{-- التقرير --}}</label>
                                            <textarea name="sr_report_text" id="sr_report_text" cols="100" rows="5" readonly></textarea>
                                            <a href="" type="button" id="sr_attached_file" style="display: none" download>{{__('translate.Download Report Attachment')}}{{-- تنزيل الملف المرفق مع التقرير --}}</a>
                                        </div>
                                    </div>
                                </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

