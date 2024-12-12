@extends('layouts.app')
@section('title')
    تفاصيل التقييم
@endsection
@section('header_title')
    تفاصيل التقييم
@endsection
@section('header_title_link')
    تفاصيل التقييم
@endsection
@section('header_link')
    تفاصيل التقييم
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-hover">
                                <tbody>
                                    @foreach ($data as $key)
                                        @if (auth()->user()->u_role_id == 10)
                                            <tr>
                                                <td>{{ $key->name }}</td>
                                                <td>{{ \App\Models\Course::where('c_id', $key['registrations'][0]['r_course_id'])->first()->c_name }}

                                                <td>
                                                    <span
                                                        class="">{{ '50 / ' . $key['registrations'][0]['university_score'] ?? 'لم يتم التقييم بعد' }}</span>
                                                </td>
                                                <td>
                                                    @if ($key->submission_status == false)
                                                        <a href="{{ route('students.evaluation.evaluation_submission_page', ['registration_id' => \App\Models\Registration::where('r_student_id', $key->u_id)->first()->r_id, 'evaluation_id' => $id]) }}"
                                                            class="btn btn-xs btn-primary">تقييم</a>
                                                    @else
                                                        <p class="badge bg-success">تم التقييم</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @elseif(auth()->user()->u_role_id == 2)
                                            <tr>
                                                <td>{{ $key->name }}</td>
                                                <td>
                                                    @if ($key->submission_status == false)
                                                        <a href="{{ route('students.evaluation.evaluation_submission_page', ['registration_id' => $key->u_id, 'evaluation_id' => $id]) }}"
                                                            class="btn btn-xs btn-primary">تقييم</a>
                                                    @else
                                                        <button class="badge bg-success">تم التقييم</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @elseif(auth()->user()->u_role_id == 6)
                                            <tr>
                                                <td>{{ $key->name }}</td>
                                                <td>{{ \App\Models\Course::where('c_id', $key['registrations'][0]['r_course_id'])->first()->c_name }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="">{{ '50 / ' . $key['registrations'][0]['company_score'] ?? 'لم يتم التقييم بعد' }}</span>
                                                </td>
                                                <td>
                                                    @if ($key->submission_status == false)
                                                        <a href="{{ route('students.evaluation.evaluation_submission_page', ['registration_id' => $key->u_id, 'evaluation_id' => $id]) }}"
                                                            class="btn btn-xs btn-primary">تقييم</a>
                                                    @else
                                                        <button class="badge bg-success">تم التقييم</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
