{{-- @if ($data->isEmpty())
    <h6 class="alert alert-danger">لا يوجد تخصصات مضافة لهذا المشرف لعرضها</h6>
@else
    @foreach ($data as $key)
        <div class="col-sm-6">
            <div class="card o-hidden border-0">
                <div class="bg-secondary b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="media-body row">
                            <div class="col-md-10">
                                <h5 class="mb-0 counter">{{$key->majors->m_name}}</h5>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag icon-bg"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif --}}
