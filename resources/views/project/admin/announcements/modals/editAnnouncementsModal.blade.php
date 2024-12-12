<div class="modal fade show" id="editAnnouncementsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">

                        <div class="col-md-4 text-center" style="margin:auto">


                                <h1><span class="fa fa-plus" style="text-align: center; font-size:80px; "></span></h1>


                                <h1>{{__('translate.edit_announcement')}}{{-- تعديل اعلان --}}</h1>

                                <hr>
                                <p>{{__('translate.In this section, you can edit the announcements')}}{{-- في هذا القسم يمكنك تعديل الاعلان المراد --}}</p>


                        </div>

                        <div class="col-md-8">
                            <form class="form-horizontal" id="editAnnouncementsForm" action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label  for="textinput"><span style="color: red">*</span>{{__('translate.announcement_title')}}{{-- عنوان الاعلان --}} :</label>
                                            <div class="input-container">
                                                <input class="form-control" type="text" id="edit_a_title" name="edit_a_title">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label   for="textinput"><span style="color: red">*</span>{{__('translate.announcement_content')}}{{-- محتوى الاعلان--}} :</label>
                                            <div class="col-lg-12">
                                                <textarea id="edit_a_content" name="edit_a_content" type="text"
                                                    class="form-control btn-square input-md" rows="6"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="textinput">{{__('translate.announcement_photo')}}{{-- صورة الاعلان--}} :</label>
                                            <div class="col-lg-12">
                                                <input id="edit_a_image" name="edit_a_image" type="file"
                                                    class="form-control btn-square input-md" rows="6">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>

                    </div>
                </div>
                <input id="edit_a_id" name="a_id" hidden type="text" class="form-control btn-square input-md">

                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary" id="edit_announcement">{{__('translate.edit_announcement')}}{{-- تعديل اعلان --}}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
