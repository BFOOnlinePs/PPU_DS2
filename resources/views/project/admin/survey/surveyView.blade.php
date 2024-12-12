@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{--الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.add_survey')}}{{--اضافة استبيان --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('admin.survey.index') }}">{{__('translate.surveys')}}{{--الاستبيانات--}}</a>
@endsection
@section('style')
<style>





.form-section {
    margin-bottom: 20px;
}


.form-control {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.btn-submit {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: #45a049;
}

.form-footer {
    text-align: right;
}
/* public/css/styles.css */



.survey-form-container {
    max-width: 800px;
    margin: 1px auto;
    margin-bottom:10px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.form-header {
    text-align: center;
    margin-bottom: 20px;
}

.question {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.add-question-btn {
    text-align: center;
    margin-top: 20px;
}

.add-question-btn button {
    background-color: #3498db;
    color: #fff;
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.add-question-btn button:hover {
    background-color: #2980b9;
}

.form-footer {
    text-align: center;
    margin-top: 20px;
}
.optionPlus{

    justify-content: space-between;
    display: flex;
 
}
</style>
@endsection
@section('content')
<div class="survey-form-container" >
                    <div class="card card-absolute"  >
                          <div class="card-header bg-primary" >
                            <h5 id="s_title" name="s_title" value="{{$data->s_title}}"class="text-white">{{$data->s_title}}</h5>
                          </div>

  


                          <div class="card-body">






<div class="mb-3 row">
  <div class="col-lg-12">
    {{$data->s_description }}
  </div>
</div>
<hr>
<form class="form-horizontal"  id="submitSurvey" method="POST"  action="{{ route('admin.survey.submitSurvey') }}">
    @csrf
    <input hidden id="s_id" name="s_id" value="{{$data->s_id}}">
    <input hidden id="questionsCount" name="questionsCount" value="{{count($questions)}}">
    <input hidden id="questions" name="questions" value="{{$questions}}">

<div id="questions-container">
@foreach($questions as $question)
    <div class="question row mb-3">
        <div class="col-md-6" style="width:100%">
            <label>@if($question->sq_question_required == 1) <span style="color: red">*</span> @endif {{__('translate.question')}} {{-- السؤال --}}  {{$loop->iteration}}: {{$question->sq_question_text}}</label>
            @if($question->sq_question_type == 'short_answer')
                <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                <input type="text" class="form-control" name="question{{$question->sq_id}}_answer" placeholder="" @if($question->sq_question_required == 1) required @endif>
            @elseif($question->sq_question_type == 'paragraph')
                <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                <textarea class="form-control" name="question{{$question->sq_id}}_answer" placeholder="" @if($question->sq_question_required == 1) required @endif></textarea>
            @elseif($question->sq_question_type == 'multiple_choice')        
            <input hidden id="questionType{{$question->sq_id}}" name="questionType{{$question->sq_id}}" value="{{$question->sq_question_type}}">
            <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                @foreach($question->options as $option)
                    <div class="answer-container col-md-12" >
                        <div style="display:flex">
                            <input class="form-check-input" type="checkbox" name="question{{$question->sq_id}}_answer[]" id="question{{$question->sq_id}}_option{{$option->sqo_id}}" value="{{$option->sqo_option_text}}">
                            <label class="form-check-label" style="margin-left:1%;margin-right:1%" for="question{{$question->sq_id}}_option{{$option->sqo_id}}">
                                {{$option->sqo_option_text}}
                            </label>
                        </div>
                    </div>
                @endforeach
                <div id="errorMessage_multipleChoice{{$question->sq_id}}" style="color:#dc3545" class="error-message"></div>
            @elseif($question->sq_question_type == 'single_choice')
            <input hidden id="questionType{{$question->sq_id}}" name="questionType{{$question->sq_id}}" value="{{$question->sq_question_type}}">
            <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                @foreach($question->options as $option)
                    <div class="answer-container col-md-12" >
                        <div style="display:flex">
                        <input class="form-check-input" style="margin-left:1%; margin-right:1%" type="radio" name="question{{$question->sq_id}}_answer" id="question{{$question->sq_id}}_option{{$option->sqo_id}}" value="{{$option->sqo_option_text}}" @if($question->sq_question_required == 1) required @endif>
                        <label class="form-check-label" for="question{{$question->sq_id}}_option{{$option->sqo_id}}">
                            {{$option->sqo_option_text}}
                        </label>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <hr>
@endforeach







</div>

<div class="f1-buttons">              
                <button type="submit" class="btn btn-primary">{{__('translate.submit')}}{{--حفظ--}}</button>    
            </div>
</div>

</form>


</fieldset>
</div>
</div>
    
  









@endsection


@section('script')
<script>
        let submitSurveyForm = document.getElementById("submitSurvey");

        submitSurveyForm.addEventListener("submit", (e) => {
            questions=[];
            questions=JSON.parse(document.getElementById('questions').value);
console.log(questions);
questions.forEach((question) => {
    console.log(question);

    if (question.sq_question_type == 'multiple_choice') {
        if(question.sq_question_required == 1){
        const checkboxes = document.querySelectorAll(`input[name="question${question.sq_id}_answer[]"]:checked`);
        const errorMessage = document.getElementById(`errorMessage_multipleChoice${question.sq_id}`);

        if (checkboxes.length === 0) {
            console.log("no");
            e.preventDefault();
            errorMessage.textContent ="{{__('translate.multiSelection_surveySubmit')}}" // 'الرجاء اختيار واحد على الأقل';

            return false;
        } else {
            console.log("yes");
            errorMessage.textContent = '';
            return false;
        }
    }
}
});


});
</script>

@endsection