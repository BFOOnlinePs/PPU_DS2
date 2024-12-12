<div class="modal fade show" id="AddNoteModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" >
                            <h1><span class="fa fa-sticky-note" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__("translate.The Notes")}} {{--كتابة ملاحظات--}}</h3>
                            <hr>
                            <p>{{__("translate.In this section, you can write notes about student's report")}} {{--في هذا القسم يمكن كتابة ملاحظات عن التقرير للطالب --}}</p>
                        </div>
                        <div class="col-md-8">
                            <form class="form-horizontal" id="addNoteForm" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <input type="hidden" name="sr_id" id="note_sr_id">
                                            <label for=""> {{__("translate.Report Notes")}}{{--ملاحظات عن التقرير--}}</label>
                                            <textarea name="sr_notes_company" id="sr_notes_company" cols="30" rows="10"  class="form-control" required></textarea>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary" id="button_add_course_in_modal">حفظ</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
