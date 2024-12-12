<div class="modal" id="confirmIntegrationModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-deactive">{{__('translate.Confirm Synchronization')}}{{-- تأكيد عمل مزامنة --}}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="sa_id">
                <br>
                <h6>{{__('translate.Are you sure you want to synchronize data between the Excel file and the database?')}}{{-- هل أنت متأكد من عمل المزامنة ما بين ملف الإكسل وقاعدة البيانات؟ --}}</h6>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="submit_form()">{{__('translate.Confirm')}}{{-- تأكيد --}}</button>
                <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
            </div>
        </div>
    </div>
</div>
