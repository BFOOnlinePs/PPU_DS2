<div class="modal fade show" id="add_message_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('training_supervisor.conversation.create_message') }}" method="post">
            @csrf
            <input type="hidden" value="{{ $id }}" name="m_conversation_id">
            <div class="modal-content" style="border: none;">
                <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5>اضافة رسالة</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">نص الرسالة</label>
                                <textarea name="m_message_text" class="form-control" id="" cols="30" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Close')}}{{-- إغلاق --}}</button>
                    <button type="submit" class="btn btn-primary">اضافة</button>
                </div>
            </div>
        </form>
    </div>
</div>
