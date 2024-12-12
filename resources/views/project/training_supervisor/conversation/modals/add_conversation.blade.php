<div class="modal fade show" id="add_conversation_modal">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('training_supervisor.conversation.create') }}" method="post">
            @csrf
            {{-- <input type="hidden" value="{{ $id }}" name="m_conversation_id"> --}}
            <div class="modal-content" style="border: none;">
                <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5>انشاء رسالة جديدة</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">العنوان</label>
                                <input required type="text" name="c_name" placeholder="ضع عنواناً ..." class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">محتوى الرسالة</label>
                                <textarea name="c_message" id="" class="form-control" cols="30" rows="3" placeholder="ابدا بكتابة الرسالة ..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">اضافة اشخاص الى المحادثة</label>
                                <select name="users_ids[]" required class="form-control js-example-basic-single" multiple name="" id="">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->u_id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
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
