<div class="modal fade show" id="ShowEventModalForAll" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" >
                            <h1><span class="fa fa-calendar" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__('translate.Information about the event')}}{{-- معلومات عن الحدث --}}</h3>
                            <hr>
                            <p>{{__('translate.In this section, you can view information about the event')}}{{-- في هذا القسم يمكنك عرض معلومات عن الحدث--}}</p>
                        </div>
                        <div class="col-md-8">
                            <form class="form-horizontal" id="show_event_information_for_all">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{__('translate.Title')}}{{-- العنوان --}}</label>
                                        <input type="text" class="form-control" id="show_e_title_for_all" disabled>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">{{__('translate.Description')}}{{-- الوصف --}}</label>
                                        <textarea type="text" class="form-control" id="show_e_description_for_all" disabled></textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{__('translate.The category to which the event appears')}}{{-- الفئة التي يظهر لها الحدث --}}</label>
                                        <input type="text" class="form-control" id="show_e_type_for_all" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{__('translate.Selecting the category')}}{{-- تحديد الفئة --}}</label>
                                        <input type="text" class="form-control" id="show_e_id_type_for_all" disabled>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{__('translate.From:')}}{{-- من --}}</label>
                                        <input type="date" class="form-control" id="show_e_start_date_for_all" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">{{__('translate.To:')}}{{-- إلى --}}</label>
                                        <input type="date" class="form-control" id="show_e_end_date_for_all" disabled>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}} {{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
