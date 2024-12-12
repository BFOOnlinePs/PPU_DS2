<div class="modal" id="confirmDeleteModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-deactive">{{__("translate.Delete Students and Thier Information Confirmation")}}{{--تأكيد حذف الطلاب وبياناتهم--}}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <br>
                <h6>{{__('translate.Are you sure frome deleting students and thier information between the specified dates?')}}{{--هل أنت متأكد من حذف الطلاب وبياناتهم في الفترة المحددة؟--}}</h6>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">{{__('translate.Delete')}}{{--حذف--}}</button>
                <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
            </div>
        </div>
    </div>
</div>
