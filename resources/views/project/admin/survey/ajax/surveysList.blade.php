<div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col" style="display:none;">id</th>
                            <th scope="col">{{__('translate.survey_title')}} {{-- اسم الاستبيان --}}</th>
                            <th scope="col">{{__('translate.target_group')}}{{--  الفئة المستهدفة --}}</th>
                            <th scope="col">{{__('translate.added_by')}}{{-- منشئ الاستبيان  --}}</th>
                            <th scope="col">{{__('translate.start_date')}}{{-- تاريخ الظهور  --}}</th>
                            <th scope="col">{{__('translate.end_date')}} {{--  تاريخ الانتهاء --}}</th>
                            <th scope="col">{{__('translate.Operations')}} {{--  العمليات  --}}</th>

                        </tr>
                    </thead>
                    <tbody>
                    @if ($data->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
                        </tr>
                    @else
                        @foreach($data as $key)
                            <tr>
                                <td style="display:none;">{{ $key->s_id }}</td>
                                <td>{{$key->s_title}}</td>
                                <td>{{$key->targets->st_name}}</td>
                                <td>{{$key->s_added_by}} </td>
                                <td>{{$key->s_start_date}}</td>
                                <td>{{$key->s_end_date}}</td>
                                <td>
                                   <button class="btn btn-info" onclick='location.href="{{route("admin.survey.surveyView",["id"=>$key->s_id])}}"'><i class="fa fa-info"></i></button>
                                   <button class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                   <button class="btn btn-primary" onclick="showDeleteSurveyModal({{ $key->s_id }})"><i class="fa fa-trash"></i></button>
                                </td>
                          
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                </table>
            </div>