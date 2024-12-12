@if ($supervisorAssistants->isEmpty())
    <h6 class="alert alert-danger">{{__('translate.No Assistants Added to This Supervisor')}}{{--لا يوجد مساعدين إداريين لهذا المشرف--}}</h6>
@else
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{__('translate.Academic Supervisor Assistant Name')}}{{-- اسم المساعد الإداري --}}</th>
                @if (auth()->user()->u_role_id == 1)
                    <th>
                        {{__('translate.Remove the assistant assigned to this supervisor')}} {{-- حذف المساعد الإداري لهذا المشرف --}}
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($supervisorAssistants as $assistant)
                <tr>
                    {{-- <td>{{$assistant->assistantUser->name}}</td> --}}
                    <td><a href="{{route('admin.users.details',['id'=>$assistant->assistantUser->u_id])}}">{{$assistant->assistantUser->name}}</a></td>
                    @if (auth()->user()->u_role_id == 1)
                        <th>
                            <button class="btn btn-lg" onclick="showAlertDelete({{$assistant->sa_id}})" type="button"><span class="fa fa-trash "></span></button>
                        </th>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

