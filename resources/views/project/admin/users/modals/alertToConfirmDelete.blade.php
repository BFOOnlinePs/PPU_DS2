<div class="modal" id="confirmDeleteTraining">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-deactive">{{__("translate.Delete Student's Training Confirmation")}}{{--تأكيد حذف تدريب الطالب--}}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="student_company_id">
                <br>
                <h6>{{__('translate.Are you sure from delete this training?')}}{{--هل أنت متأكد من حذف التدريب؟--}}</h6>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="confirmDeleteTraining()">{{__('translate.Confirm')}}{{--تأكيد--}}</button>
                <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
            </div>
        </div>
    </div>
</div>
