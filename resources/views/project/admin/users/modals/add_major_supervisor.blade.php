<div class="modal fade show" id="AddMajorModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row p-3 m-5">
                    <div class="col-md-4 text-center">
                        <h1><span class="fa fa-book" style="text-align: center; font-size:80px; "></span></h1>
                        <h3>{{ __('translate.Add a Major for the Academic Supervisor') }}{{-- تسجيل تخصص للمشرف الأكاديمي --}}</h3>
                        <hr>
                        <p>{{ __('translate.In this section, you can add major for the current supervisor') }}
                            {{-- في هذا القسم يمكن تسجيل تخصص للمشرف الأكاديمي --}}</p>
                    </div>
                    <div class="col-md-8">
                        <form class="form-horizontal" id="addMajorForm" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 row">
                                        <label for="">{{ __('translate.Major') }} {{-- التخصص --}}</label>
                                        <select autofocus class="js-example-basic-single col-sm-12" name="m_id"
                                            id="select-majors">
                                            @foreach ($majors as $major)
                                                <option value="{{ $major->m_id }}">{{ $major->m_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer ">
                <button type="submit" class="btn btn-primary"
                    id="button_add_major_in_modal">{{ __('translate.Add Major') }}{{-- تسجيل التخصص --}}</button>
                <button type="button" class="btn btn-light"
                    data-bs-dismiss="modal">{{ __('translate.Cancel') }}{{-- إلغاء --}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
