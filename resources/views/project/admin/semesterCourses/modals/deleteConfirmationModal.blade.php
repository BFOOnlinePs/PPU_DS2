<div class="modal" id="deleteModal">
    <div class="modal-dialog modal-dialog-centered">
     <div class="modal-content">
      
      <div class="modal-body">
       <br>
       <h6 id="p-deactive">{{__('translate.Are you sure from removing course from current semester?')}}{{--هل أنت متأكد من حذف تدريب عملي من الفصل الحالي؟--}}</h6>
       <br>
       {{-- <p id='deactiveName'></p> --}}
     </div>
     <div class="modal-footer">
      <button id="b-deactive" type="button" class="btn btn-danger" onclick="deleteCourse()">{{__('translate.Remove')}}{{--حذف--}}</button>
      <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
      </div>
     </div>
    </div>
</div>
