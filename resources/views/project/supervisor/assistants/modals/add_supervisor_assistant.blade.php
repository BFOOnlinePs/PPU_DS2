<div class="modal fade show" id="AddAssistantModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" >
                            <h1><span class="fa fa-user-circle" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__('translate.Assigne Assistant to the Academic Supervisor')}}{{-- إضافة مساعد إداري للمشرف الأكاديمي --}}</h3>
                            <hr>
                            <p >{{__('translate.In this section, you can add an assistant to the academic supervisor')}}{{-- في هذا القسم يمكن إضافة مساعد إداري للمشرف الأكاديمي --}}</p>
                        </div>
                        <div class="col-md-8">
                            <form class="form-horizontal" enctype="multipart/form-data" id="addAssistantForm">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Academic Supervisors Assistants')}}{{-- المساعد الإداري --}} :</label>
                                            <select autofocus class="js-example-basic-single col-sm-12" name="assistant_id" id="select-assistant">
                                                @foreach ($assistants as $assistant)
                                                    <option value="{{$assistant->u_id}}">{{$assistant->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary" onclick="add_assistant()">{{__('translate.Add Academic Supervisor Assistant')}}{{-- إضافة مساعد إداري --}}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
