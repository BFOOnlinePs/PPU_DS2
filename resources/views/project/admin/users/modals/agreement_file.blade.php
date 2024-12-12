<div class="modal fade show" id="AgreementFileModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" >
                            <h1><span class="fa fa-book" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__('translate.Display Approval File')}}{{--عرض ملف الموافقة--}}</h3>
                            <hr>
                            <p>{{__('translate.In this section, you can display')}} {{__('translate.approval file')}}{{--في هذا القسم يمكنك عرض ملف الموافقة--}}</p>
                        </div>
                        <div class="col-md-8">
                            <form class="form-horizontal" id="agreementFileForm" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <iframe id="view_attachment_result" src="" frameborder="0" width="100%" style="height: 550px">

                                            </iframe>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
