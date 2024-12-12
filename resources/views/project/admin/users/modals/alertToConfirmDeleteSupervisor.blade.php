<div class="modal" id="confirmDeleteSupervisorModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- <div class="modal-header"> --}}
                {{-- <h5 class="modal-title" id="exampleModalLabel-deactive">تأكيد حذف المشرف أكاديمي للمساعد الإداري</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            {{-- </div> --}}
            <div class="modal-body">
                <input type="hidden" id="p_id">
                <br>
                <h6>{{__('translate.Are you sure from removing this supervisor?')}}{{--هل أنت متأكد من حذف المشرف الأكاديمي للمساعد الإداري؟--}}</h6>
                <br>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="sa_id">
                <button type="button" class="btn btn-danger" onclick="deleteSupervisor()">{{__('translate.Confirm')}}{{--تأكيد--}}</button>
                <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
            </div>
        </div>
    </div>
</div>
