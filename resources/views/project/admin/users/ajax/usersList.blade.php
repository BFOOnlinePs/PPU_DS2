<table class="table table-bordered table-striped" id="users_table">
    <thead>
        <tr>
            @if ($user_role != null)
                @if ($user_role == '3')
                    <th>اسم رئيس القسم</th>
                @elseif($user_role == '10')
                    <th>اسم مشرف التدريب العملي</th>
                @elseif($user_role == '2')
                    <th>اسم الطالب</th>
                @else
                    <th>اسم الطالب</th>
                @endif
            @else
                <th>اسم المستخدم</th>
            @endif
            <th>{{ __('translate.Phone Number') }}</th>
            @if ($user_role != null)
                @if ($user_role != '3' && $user_role != '6')
                    <th>{{ __('translate.tawjihi_rate') }}</th>
                    <th>{{ __('translate.Major') }}</th>
                @endif
            @endif
            <th>نوع المستخدم</th>
            @if ($user_role != null)
                @if ($user_role == '2')
                    {{-- <th>المشرف الخاص بالطالب</th> --}}
                @endif
            @endif
            <th style="max-width: 100px">{{ __('translate.View Details') }} {{-- عرض تفاصيل --}}</th>

        </tr>
    </thead>
    <tbody>
        @if ($data->isEmpty())
            <tr>
                <td colspan="5" class="text-center">
                    <span>{{ __('translate.No Users to Display') }}{{-- لا يوجد مستخدمين لعرضهم --}}</span></td>
            </tr>
        @else
            @foreach ($data as $key)
                <tr id="user-row-{{ $key->id }}">
                    <td>{{ $key->name }}</td>
                    <td>{{ $key->u_phone1 }}</td>
                    @if ($user_role != null)
                        @if ($user_role != '3' && $user_role != '6')
                            <td>{{ $key->u_tawjihi_gpa }}</td>
                            <td>{{ $key->major->m_name ?? 0 }}</td>
                        @endif
                    @endif
                    <td>
                        @if ($key->u_role_id == 1)
                            ادمن
                        @elseif($key->u_role_id == 2)
                            طالب
                        @elseif($key->u_role_id == 3)
                            مشرف اكاديمي
                        @elseif($key->u_role_id == 4)
                            مساعد إداري
                        @elseif($key->u_role_id == 5)
                            مسؤول متابعة وتقييم
                        @elseif($key->u_role_id == 6)
                            مدير شركة
                        @elseif($key->u_role_id == 7)
                            مسؤول تدريب
                        @elseif($key->u_role_id == 8)
                            مسؤول التواصل مع الشركات
                        @elseif($key->u_role_id == 9)
                            مسؤول المتابعة
                        @elseif($key->u_role_id == 10)
                            مشرف التدريب العملي
                        @endif
                    </td>
                    @if (request()->route()->hasParameter('id'))
                        @if ($key->u_role_id != '2' && $key->u_role_id != '6')
                            {{-- <td>
                        <select onchange="add_training_supervisor({{ $key->r_student_id }} , this.value)" class="form-control" name="" id="">
                            <option value="">اختر المشرف ...</option>
                            @foreach ($supervisors as $supervisor)
                                <option @if ($supervisor->u_id == $key->supervisor_id) selected @endif value="{{ $supervisor->u_id }}">{{ $supervisor->name }}</option>
                            @endforeach
                        </select>
                    </td> --}}
                            <td>
                                <select onchange="change_user_role({{ $key->u_id }} , this.value)"
                                    class="form-control" name="" id="">
                                    <option value="">اختر الصلاحية ...</option>
                                    @foreach ($roles as $role)
                                        <option @if ($role->r_id == $key->u_role_id) selected @endif
                                            value="{{ $role->r_id }}">{{ $role->r_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        @endif
                    @endif
                    {{--            @if ($key->u_status == 0) --}}
                    {{--            <td class="text-danger" id="td-{{$key->id}}">{{__('translate.Deactivated')}} --}}{{-- غير مفعل --}}{{-- </td> --}}
                    {{--            <td class="text-success" id="td-{{$key->id}}">{{__('translate.Active')}} --}}{{-- مفعل --}}{{-- </td> --}}
                    {{--            @endif --}}
                    <td class="text-center">
                        <a href="{{ route('admin.users.details', ['id' => $key->u_id]) }}"
                            class="btn btn-primary btn-xs"><span class="fa fa-search"></span></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
