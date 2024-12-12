@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{--الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.submit_survey')}} {{-- تسليم الاستبيان --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('admin.survey.index') }}">{{__('translate.surveys')}}{{--الاستبيانات--}}</a>
@endsection
@section('style')
<style>
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
  {{__('translate.your_response_has_been_recorded')}} {{-- تم تسليم ردك --}}


  </div>
</div>
<hr>

<form class="form-horizontal" id="submitSurvey" >
    @csrf
    <input hidden id="s_id" name="s_id" value="{{$data->s_id}}">

    <div id="questions-container">
        @foreach($data->questions as $question)
           
                <div class="question row mb-3">
                    <div class="col-md-6" style="width:100%">
                        <label>{{__('translate.question')}} {{$loop->iteration}}: {{$question->sq_question_text}}</label>
                        @if($question->sq_question_type == 'short_answer')
                            <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                            <input type="text" disabled class="form-control" @if(count($question->answers)!=0) value="{{$question->answers[0]->ss_question_answer}}"@endif name="question{{$question->sq_id}}_answer" placeholder="" @if($question->sq_question_required == 1) required @endif>
                        @elseif($question->sq_question_type == 'paragraph')
                            <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                            <textarea class="form-control" disabled  name="question{{$question->sq_id}}_answer" placeholder="" @if($question->sq_question_required == 1) required @endif>@if(count($question->answers)!=0){{$question->answers[0]->ss_question_answer}}@endif</textarea>
                        @elseif($question->sq_question_type == 'multiple_choice')        
                            <input hidden id="questionType{{$question->sq_id}}" name="questionType{{$question->sq_id}}" value="{{$question->sq_question_type}}">
                            <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                            @foreach($question->options as $option)
                                <div class="answer-container col-md-12">
                                    <div style="display:flex">
                                        <input class="form-check-input" disabled type="checkbox"  name="question{{$question->sq_id}}_answer[]" id="question{{$question->sq_id}}_option{{$option->sqo_id}}" value="{{$option->sqo_option_text}}"@foreach($option->answers as $answer)
    @if($answer->ss_question_answer == $option->sqo_option_text)
        checked
    @endif
@endforeach
>
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
                                <div class="answer-container col-md-12">
                                    <div style="display:flex">
                                        <input class="form-check-input" disabled  style="margin-left:1%; margin-right:1%" type="radio" name="question{{$question->sq_id}}_answer" id="question{{$question->sq_id}}_option{{$option->sqo_id}}" value="{{$option->sqo_option_text}}" @if($question->sq_question_required == 1) required @endif @foreach($option->answers as $answer)
    @if($answer->ss_question_answer == $option->sqo_option_text)
        checked
    @endif
@endforeach
>
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

   
</form>


</fieldset>
</div>
</div>
</div>
    
  









@endsection

