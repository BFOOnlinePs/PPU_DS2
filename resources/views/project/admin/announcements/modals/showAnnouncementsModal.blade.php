<div class="modal fade show" id="showAnnouncementsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" style="margin:auto">
                                <h1><span class="fa fa-plus" style="text-align: center; font-size:80px; "></span></h1>
                                <h1>{{__('translate.display_announcement')}}{{-- استعراض الاعلان  --}}</h1>
                                <hr>
                                <p>{{__('translate.In this section, you can display an announcements')}}{{-- في هذا القسم يمكنك استعراض اعلان  --}}</p>
                        </div>

                        <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <div class="input-container">
                                                <label  for="textinput">{{__('translate.announcement_title')}}{{-- عنوان الاعلان --}} :</label>
                                                <input disabled class="form-control" type="text" id="show_a_title" name="show_a_title">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <div class="col-lg-12">
                                                <label   for="textinput">{{__('translate.announcement_content')}}{{-- محتوى الاعلان--}} :</label>
                                                <textarea disabled id="show_a_content" name="show_a_content" type="text"
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
                                                <img id="show_a_image" name="show_a_image" type="file"
                                                    class="form-control btn-square input-md">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>



