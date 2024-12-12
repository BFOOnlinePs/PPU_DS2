<div class="modal" id="confirmPaymentModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-deactive">{{__('translate.Receipt Confirmed')}}{{-- تأكيد استلام الدفعة --}}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="p_id">
                <br>
                <h6>{{__('translate.Are you sure you want to confirm payment receipt?')}}{{-- هل أنت متأكد من تأكيد استلام الدفعة --}}</h6>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="confirmPayment()">{{__('translate.Confirm')}}{{-- تأكيد --}}</button>
                <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
            </div>
        </div>
    </div>
</div>
