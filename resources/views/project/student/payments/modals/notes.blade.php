<div class="modal fade show" id="NotesModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center" style="margin: auto">
                            <h1><span class="fa fa-sticky-note" style="text-align: center; font-size:80px; "></span></h1>
                            <h1 id="header"></h1>
                            <hr>
                            <p id="explain"> </p>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="textinput" class="col-lg-12 col-form-label" id="notes"></label>
                                    <div class="col-lg-12">
                                        <textarea disabled class="form-control btn-square" id="textinput"></textarea>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Close')}}{{-- إغلاق --}}</button>
                </div>
        </div>
    </div>
</div>
