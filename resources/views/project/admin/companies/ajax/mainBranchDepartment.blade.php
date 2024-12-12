<div class="col-md-8">
                    <div class="ribbon-wrapper-right card" id="departments_summary_area" >
                      <div class="card-body">
                        <div class="ribbon ribbon-clip-right ribbon-right ribbon-primary">{{__('translate.Main Branch Departments')}}{{--أقسام الفرع الرئيسي--}}</div>
                        <div id="departments">
                             <div class="row">
                             @foreach($company->companyBranch as $key)
           @if($key->b_main_branch == 1)
          @foreach($key->companyDepartments as $key1)
          <div class="col-md-4">
          <input class="f1-last-name form-control" id="main_branch_departments" name="main_branch_departments" value="{{$key1->d_name}}" disabled>
    </div>
            @endforeach
            @endif
            @endforeach


                        </div>
                    </div>
                    </div>
                </div>
    </div>
    </div>
