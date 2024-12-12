                                        <div id="DepartmentList">
                                      <input id="companyDepartments" name="companyDepartments" value="{{$companyDepartments}}" hidden>
                                      @foreach($companyDepartments as $key1)
                                          <input hidden id="d_id" name="d_id" value="{{$key1->d_id}}">
                                          <div class="col-md-8">
                                              <input class="f1-last-name form-control" name="d_name_{{$key1->d_id}}" id="d_name_{{$key1->d_id}}" value="{{$key1->d_name}}">
                                          </div>
                                          <br>
                                      @endforeach

                                      <input hidden id="c_id" name="c_id" value="{{$company->c_id}}">
                                  </div>
