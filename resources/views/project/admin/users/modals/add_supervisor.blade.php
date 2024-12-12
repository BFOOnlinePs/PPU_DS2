<div class="modal fade show" id="AddSupervisorModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" >
                            <h1><span class="fa fa-book" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__('translate.Assigne Supervisor to the Assistant')}}{{--تسجيل مشرف أكاديمي لمساعد إداري--}}</h3>
                            <hr>
                            <p >{{__('translate.In this section, you can add supervisor to the academic supervisor assistant')}}{{--في هذا القسم يمكن تسجيل مشرف أكاديمي للمساعد إداري--}}</p>
                        </div>
                        <div class="col-md-8">
                            <form class="form-horizontal" id="addSupervisorForm" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="">{{__('translate.Academic Supervisor')}}{{--المشرف الأكاديمي--}}</label>
                                            <select autofocus class="js-example-basic-single col-sm-12" id="select-supervisors">
                                                @if(!empty($supervisors))
                                                    @foreach ($supervisors as $supervisor)
                                                        <option value="{{$supervisor->u_id}}">{{$supervisor->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-primary" onclick="add_supervisor({{$user->u_id}})">{{__('translate.Assigne Academic Supervisor')}}{{--تسجيل مشرف أكاديمي--}}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
