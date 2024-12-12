@extends('layouts.app')
@section('title')
    صفحة التقييم
@endsection
@section('header_title')
    صفحة التقييم
@endsection
@section('header_title_link')
    صفحة التقييم
@endsection
@section('header_link')
    صفحة التقييم
@endsection
@section('content')  
    <div class="row">
        <form action="{{ route('students.evaluation.evaluation_submission_create') }}" method="post" class="col-md-12">
            @csrf
            <input type="hidden" name="es_evaluation_id" value="{{ $evaluation->e_id }}">
            @if(!auth()->user()->u_role_id == 2)
                <input type="hidden" name="registration_id" value="{{ $registration->r_id }}">
                <input type="hidden" name="es_evaluatee_id" value="{{ $registration->supervisor_id }}">
            @elseif(auth()->user()->u_role_id == 6)
                <input type="hidden" name="registration_id" value="{{ $registration->r_id }}">
            @elseif(auth()->user()->u_role_id == 10)
                <input type="hidden" name="registration_id" value="{{ $registration->r_id }}">
            @else
                <input type="hidden" name="registration_id" value="{{ $registration }}">
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">المعايير</label>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>
                                            <div class="row">
                                                <div class="col">
                                                    1
                                                </div>
                                                <div class="col">
                                                    2
                                                </div>
                                                <div class="col">
                                                    3
                                                </div>
                                                <div class="col">
                                                    4
                                                </div>
                                                <div class="col">
                                                    5
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($criteria as $key)
                                    <tr>
                                        <td>{{ $key->c_criteria_name }}</td>
                                        <td>
                                            <div class="row">
                                                <input type="hidden" name="criteria_ids[]" value="{{ $key->c_id }}">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <div class="col">
                                                        <input required type="radio" name="criteria[{{ $key->c_id }}]" id="criteria_{{ $key->c_id }}_{{ $i }}" value="{{ $i }}" {{ old('criteria.'.$key->c_id) == $i ? 'checked' : '' }}>
                                                    </div>
                                                @endfor
                                            </div>
                                            @if ($errors->has('criteria.' . $key->c_id))
                                                <span class="text-danger">{{ $errors->first('criteria.' . $key->c_id) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="es_notes">Notes</label>
                                <textarea name="es_notes" id="es_notes" class="form-control">{{ old('es_notes') }}</textarea>
                                @if ($errors->has('es_notes'))
                                    <span class="text-danger">{{ $errors->first('es_notes') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary">حفظ</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')

@endsection
