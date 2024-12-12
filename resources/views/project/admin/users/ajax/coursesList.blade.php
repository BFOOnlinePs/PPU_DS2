<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>{{__('translate.Course Name')}} {{-- اسم التدريب العملي --}}</th>
            <th>{{__('translate.Grade')}}{{-- الدرجة --}}</th>
            <th>{{__('translate.Operations')}} {{--  العمليات --}}</th>
        </tr>
    </thead>
    <tbody>
    @if ($data->isEmpty())
        <tr>
            <td colspan="3" class="text-center"><span>{{__('translate.No courses are currently enrolled')}}{{-- لا يوجد تدريبات عملية مسجلة --}}</span></td>
        </tr>
    @else
        @foreach($data as $registration)
            <tr>
                @if (auth()->user()->u_role_id == 1)
                <td><a href="{{route('admin.courses.index')}}">{{$registration->courses->c_name}}</a></td>
                @else
                    <td>{{$registration->courses->c_name}}</td>
                @endif
                <td>
                    <input class="form-control btn-square w-50" type="number" oninput="save_grade({{$registration->r_id}} , {{$registration->r_student_id}} , this.value)" value="{{$registration->r_grade}}">
                </td>
                <td>
                    <button class="btn btn-danger" onclick="delete_course_for_student({{$registration->r_course_id}})" type="button">
                        <span class="fa fa-trash">
                        </span>
                    </button>
                </td>
            </tr>
        @endforeach
        @endif
    </tbody>
</table>
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
<script>
    function add_course()
    {
        data = $('#addCoursesStudentForm').serialize();
        $.ajax({
                beforeSend: function(){
                    $('#AddCoursesStudentModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{route('admin.users.courses.student.add')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'data' : data ,
                    'id' : id
                },
                success: function(response) {
                    $('#AddCoursesStudentModal').modal('hide');
                    $('#content').html(response.html);
                    $('#add_courses_student').html(response.modal);
                },
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(jqXHR) {
                    alert(jqXHR.responseText);
                    alert('Error fetching user data.');
                }
            });
    }
</script>
