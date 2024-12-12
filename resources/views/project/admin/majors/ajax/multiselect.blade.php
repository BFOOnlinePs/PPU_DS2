
                             <div class="form-group">
                                        <label for="">{{__('translate.Academic Supervisor')}} {{-- المشرف --}}</label>
                                        <select  class="js-example-basic-single col-sm-12" id="supervisor"  multiple>
                                            @foreach ($superVisors as $super)
                                                <option   value="{{$super->u_id }}" >{{$super->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
