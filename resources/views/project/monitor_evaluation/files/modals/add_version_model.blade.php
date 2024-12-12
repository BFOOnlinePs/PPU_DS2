<div class="modal fade" id="add_version_model" role="dialog" aria-labelledby="exampleModalCenter">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('monitor_evaluation.files.create_me_version_attachment') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="mea_attachment_id" name="mea_attachment_id" value="-1">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('translate.add_copy') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('translate.file') }}</label>
                                <input type="file" name="file" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('translate.Notes') }}</label>
                                <textarea id="" cols="30" rows="2" name="mea_description" placeholder="ملاحظات" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div>
                            <table class="table table-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('translate.file_name') }}</th>
                                    <th>{{ __('translate.Notes') }}</th>
                                    <th>{{ __('translate.insert_at') }}</th>
                                </tr>
                                </thead>
                                <tbody id="file_table">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                    <button class="btn btn-primary" type="submit">{{ __('translate.add') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
