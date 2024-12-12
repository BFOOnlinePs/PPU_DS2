<div class="modal fade show" id="AddSuperVisorModal" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center">
                            <h1><span class="fa fa-briefcase" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__('translate.Assigne Supervisors To Major')}}{{--اختيار مشرفين للتخصص--}}</h3>
                            <hr>
                            <p>{{__('translate.In this section, you can assigne supervisors for mojar')}}{{--في هذا القسم يمكنك اختيار مشرفين لتخصص معين--}}</p>
                        </div>


                        <div class="col-md-8" id="test">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="AddSuperVisorForm" enctype="multipart/form-data">
                                    @csrf
                                    <input id="selected_m_id" name="m_id" hidden type="text"
                                                    class="form-control btn-square input-md">
                                     <div class="form-group">
                                        <label for="">{{__('translate.Academic Supervisor')}} {{-- المشرف --}}</label>
                                        <select  class="js-example-basic-single col-sm-12" id="supervisor"  multiple="multiple" >
                                            @foreach ($superVisors as $super)
                                                <option   value="{{$super->u_id }}" >{{$super->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

            </div>
            <div class="modal-footer ">

                <button type="submit" class="btn btn-primary" id="buttonName"></button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
