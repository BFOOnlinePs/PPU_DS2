<div class="modal fade" id="show_report_for_attendance_modal" role="dialog" aria-labelledby="exampleModalCenter">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">التقرير</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="">النص الخاص بالتقرير</label>
                        <textarea class="form-control" name="" id="report_text" cols="30" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
{{--                <button class="btn btn-primary" type="button">Save changes</button>--}}
            </div>
        </div>
    </div>
</div>
