<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>{{__('translate.Course')}}  </th>
            <th>{{__('translate.Company Name')}}   </th>
            <th>{{__('translate.Branch')}}   </th>
            <th>{{__('translate.Training Status')}}</th>
            <th>{{__('translate.Approval File')}}   </th>
            <th>{{__('translate.Operations')}}   </th>
        </tr>
    </thead>
    <tbody>
        @if ($data->isEmpty())
            <tr>
                <td colspan="6" class="text-center"><span>{{__('translate.No enrolled trainings')}}  لا يوجد تدريبات مسجلة </span></td>
            </tr>
        @else
            @foreach($data as $studentCompany)
                <tr class="@if ($studentCompany->sc_status == 3) table-danger @endif">
{{--                    <td><a href="{{route('admin.courses.index')}}">{{$studentCompany->registrations[0]->courses->c_name}}</a></td>--}}
                    <td><a href="{{route('admin.courses.index')}}"></a></td>
                    <td><a href="{{route("admin.companies.edit",['id'=>$studentCompany->company->c_id])}}">{{$studentCompany->company->c_name}}</a></td>
                    @if ($studentCompany->sc_branch_id == null)
                        <td></td>
                    @else
                        <td>{{$studentCompany->companyBranch->b_address}}</td>
                    @endif
                    <td>
                        @if ($studentCompany->sc_status == 1)
                            {{__('translate.active')}}
                        @elseif ($studentCompany->sc_status == 2)
                            {{__('translate.finished')}}
                        @else
                            {{__('translate.deleted')}}
                        @endif
                    </td>
                    <td>
                        @if (!empty($studentCompany->sc_agreement_file))
                            <a href="{{ asset('public/storage/uploads/'.$studentCompany->sc_agreement_file) }}" class="btn btn-primary fa fa-download btn-xs"  type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{__('translate.Download Approval File')}}" download></a>"تنزيل ملف الموافقة"
                            @php
                                $extension = pathinfo($studentCompany->sc_agreement_file, PATHINFO_EXTENSION);
                            @endphp
                            @if ($extension == 'png' || $extension == 'jpg' || $extension == 'pdf')
                                <a onclick="viewAttachment('{{ asset('public/storage/uploads/'.$studentCompany->sc_agreement_file) }}')" class="btn btn-primary fa fa-file btn-xs" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{__('translate.Display Approval File')}}"></a>"عرض ملف الموافقة"
                            @endif
                            <a  href="{{route('admin.users.training.place.delete.file_agreement' , ['sc_id' => $studentCompany->sc_id])}}" class="btn btn-danger fa fa-trash btn-xs" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{__('translate.Delete')}} {{__('translate.Approval File')}}"></a>"حذف ملف الموافقة"
                        @else
                            <div id="progress-container{{$studentCompany->sc_id}}" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar bg-primary progress-bar-striped" id="progress-bar{{$studentCompany->sc_id}}" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span id="progress-text{{$studentCompany->sc_id}}">Uploading...</span>
                            </div>
                            <label for="file_agreement{{$studentCompany->sc_id}}" class="btn btn-primary btn-xs">
                                <i class="fa fa-upload"></i>
                            </label>
                            <input type="file" name="file_company_student" onchange="submitFile(this, {{$studentCompany->sc_id}})" id="file_agreement{{$studentCompany->sc_id}}" style="display: none;">
                        @endif
                    </td>
                    <td>
                        @if ($studentCompany->sc_status != 3)
                            @if ($studentCompany->sc_mentor_trainer_id)
                                @if ($studentCompany->sc_department_id)
                                    <button class="btn btn-success btn-xs" onclick="open_edit_modal({{$studentCompany}} , '{{$studentCompany->companyBranch->b_address}}' , {{$studentCompany->sc_status}} , '{{$studentCompany->userMentorTrainer->name}}' , '{{$studentCompany->companyDepartment->d_name}}' , '{{$studentCompany->registrations[0]->courses->c_name}}')" type="button"><span class="fa fa-edit"></span></button>
                                @else
                                    <button class="btn btn-success btn-xs" onclick="open_edit_modal({{$studentCompany}} , '{{$studentCompany->companyBranch->b_address}}' , {{$studentCompany->sc_status}} , '{{$studentCompany->userMentorTrainer->name}}' , null , '{{$studentCompany->registrations[0]->courses->c_name}}')" type="button"><span class="fa fa-edit"></span></button>
                                @endif
                            @else
                                @if ($studentCompany->sc_department_id)
                                    <button class="btn btn-success btn-xs" onclick="open_edit_modal({{$studentCompany}} , '{{ $studentCompany->companyBranch->b_address }}' , {{$studentCompany->sc_status}} , null , '{{$studentCompany->companyDepartment->d_name}}' , '{{$studentCompany->registrations[0]->courses->c_name}}')" type="button"><span class="fa fa-edit"></span></button>
                                @else
                                    <button class="btn btn-success btn-xs" onclick="open_edit_modal({{$studentCompany}} , '' , {{$studentCompany->sc_status}} , null , null , '')" type="button"><span class="fa fa-edit"></span></button>
{{--                                    <button class="btn btn-success btn-xs" onclick="open_edit_modal({{$studentCompany}} , '{{ $studentCompany->companyBranch->b_address ?? '' }}' , {{$studentCompany->sc_status}} , null , null , '{{$studentCompany->registrations[0]->courses->c_name}}')" type="button"><span class="fa fa-edit"></span></button>--}}
                                @endif
                            @endif
                            <button class="btn btn-danger btn-xs" onclick="openAlertDelete({{$studentCompany->sc_id}})" type="button"><span class="fa fa-trash"></span></button>
                        @endif
                    </td>
                </tr>
            @endforeach
            @endif
    </tbody>
</table>
