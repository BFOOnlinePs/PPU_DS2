<div id="semsterReportTable">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th id="semsterReportTableTitle" style="background-color: #ecf0ef82;" colspan="3"></th></th>
            </tr>
          <tbody>
            <tr>
              <td class="col-md-4">{{__('translate.Total of registered students this semester')}} {{--  إجمالي الطلاب المسجلين في المساقات خلال هذا الفصل --}}</td>
              <td id="manager_summary">{{$coursesStudentsTotal}}</td>
              <td><button class="btn btn-primary" onclick='location.href="{{route("monitor_evaluation.students_courses_report")}}"'><i class="fa fa-search"></i></button></td>
            </tr>
            <tr>
              <td class="col-md-4">{{__('translate.Total of Semester Courses')}} {{--إجمالي المساقات لهذا الفصل--}}</td>
              <td id="phone_summary">{{$semesterCoursesTotal}}</td>
              <td><button class="btn btn-primary" onclick='location.href="{{route("monitor_evaluation.courses_registered_report")}}"'><i class="fa fa-search"></i></button></td>
            </tr>
            <tr id="phone2_summary_area">
              <td class="col-md-4"> {{__('translate.Total of Traning Hours for all students this semester')}} {{--إجمالي ساعات التدريب لجميع الطلاب خلال هذا الفصل--}}</td>
              <td id="phone2_summary"> {{$trainingHoursTotal}}{{--ساعات--}}{{__('translate.Hours')}}،{{$trainingMinutesTotal}}{{--دقائق--}} {{__('translate.Minutes')}} </td>
              <td><button class="btn btn-primary" onclick='location.href="{{route("monitor_evaluation.training_hours_report")}}"'><i class="fa fa-search"></i></button></td>
            </tr>
            <tr>
              <td class="col-md-4">{{__("translate.Total of Companies' Trainees this semester")}} {{--إجمالي الطلاب المسجلين في الشركات خلال هذاالفصل--}}</td>
              <td id="address_summary">{{$traineesTotal}}</td>
              <td><button class="btn btn-primary" onclick='location.href="{{route("monitor_evaluation.students_companies_report")}}"'><i class="fa fa-search"></i></button></td>
            </tr>
            <tr>
                <td class="col-md-4"> {{__('translate.Total of Companies have trainees this semester')}}{{--إجمالي الشركات المسجل بها خلال هذا الفصل--}}</td>
                <td id="address_summary">{{$semesterCompaniesTotal}}</td>
                <td><button class="btn btn-primary" onclick='location.href="{{route("monitor_evaluation.companiesReport")}}"'><i class="fa fa-search"></i></button></td>
            </tr>

          </tbody>
        </table>
    </div>
</div>
