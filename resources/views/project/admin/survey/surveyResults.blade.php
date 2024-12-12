@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{--الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.surveys_results')}}{{-- نتائج الاستبيانات  --}}
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
            {{count($question->answers)}} {{__('translate.answers')}} {{--  إجابات --}} 
            @if($question->sq_question_type == 'short_answer')
            @foreach($question->answers as $answer)
            <input type="text" class="form-control" disabled name="question{{$question->sq_id}}_answer" value="{{$answer->ss_question_answer}}">
            @endforeach
              @elseif($question->sq_question_type == 'paragraph')
                @foreach($question->answers as $answer)
                <textarea class="form-control" disabled name="question{{$question->sq_id}}_answer">{{$answer->ss_question_answer}}</textarea>
                @endforeach
              @elseif($question->sq_question_type == 'multiple_choice'|| $question->sq_question_type == 'single_choice') 
              <input hidden id="answers{{$question->sq_id}}" value="{{$question->answers}}">

              <canvas id="pieChart{{$question->sq_id}}"></canvas>       
          
            @endif
        </div>
    </div>
    <hr>
@endforeach







</div>


</div>

</form>


</fieldset>
</div>
</div>
    
  









@endsection


@section('script')

<script>
// Extract relevant data from your JSON
const questions = {!! json_encode($questions) !!};
answers=[];
options=[];
function generateBasicColors(count) {
    const basicColors = ['red', 'green', 'blue'];
    const dynamicColors = [];

    for (let i = 0; i < count; i++) {
        dynamicColors.push(basicColors[i % basicColors.length]);
    }

    return dynamicColors;
}
$(document).ready(function() {
      // Your code goes here
      // This code will only be executed once the DOM is fully loaded
      console.log( "Document is ready!");
      for(i = 0 ; i < questions.length ; i++){
   if(questions[i].sq_question_type == 'multiple_choice' || questions[i].sq_question_type == 'single_choice' ){
       answers = questions[i].answers;
       console.log(answers); 
       options = questions[i].options;
       const answerCounts = {};
       const optionCounts = {};
for(j = 0 ; j < answers.length ; j ++){

const answerText = answers[j].ss_question_answer;
    answerCounts[answerText] = (answerCounts[answerText] || 0) + 1;
    
}
for(j = 0 ; j < options.length ; j ++){

const optionText = options[j].sqo_option_text;
    optionCounts[optionText] = (optionCounts[optionText] || 0) + 1;
    
}

// Get chart data
const labels = Object.keys(answerCounts);
const data = Object.values(answerCounts);
const backgroundColors = generateBasicColors(labels.length);

// Render the pie chart
const ctx = document.getElementById('pieChart'+questions[i].sq_id).getContext('2d');
const pieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor:backgroundColors ,
        }],
    },
    options: {
        maintainAspectRatio: false, // Set to false to allow custom width and height
        responsive: false, // Set to false to prevent resizing on window resize
        // Add more options as needed
    },
});

   }
   

}    

      // Example: Changing the text of an element with id="exampleElement"
    });

// Count the occurrences of each answer

</script>

@endsection