@extends('layouts.app')
@section('title')
{{__('translate.edit_survey')}}{{--تعديل استبيان --}}
@endsection
@section('header_title')
{{__('translate.edit_survey')}}{{--تعديل استبيان --}}
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
.trach-toggle{
    display: flex;
    flex-direction: row-reverse
}
.vl {
    border-left: 1px solid #ccc;
    height: 30px; 
    margin: 0 8px; 
}


.q-link {
    display: flex;
    align-items: center; 
    border-radius: 10px;
    padding:5px;
    color:#000
}

.q-link svg {
    width: 35px; 
    margin-right: 1px; 
}

.newOption{

display:flex;
align-items: center;
}
</style>
@endsection
@section('content')
<div class="survey-form-container">
    <div class="card card-absolute">
        <div class="card-header bg-primary">
            <h5 id="titleh6" class="text-white">{{$data->s_title}}</h5>
        </div>

        <form class="form-horizontal" id="update" method="POST" action="{{ route('admin.survey.update') }}">
            @csrf
            <input hidden id="s_id" name="s_id" value="{{$data->s_id}}" >

            <div class="card-body">
                <!-- Form Name -->
                <div class="mb-3 row">
                    <div class="col-lg-12">
                        <label for="s_title1" id="s_title1">{{__('translate.survey_title')}}:</label>
                        <input id="s_title" value="{{$data->s_title}}" name="s_title" type="text" required placeholder="{{__('translate.survey_title')}} " class="form-control btn-square input-md">
                    </div>
                </div>

                <div class="mb-3 row">       
                    <label for="s_target" id="s_target">{{__('translate.target_group')}}{{--  الفئة المستهدفة --}}:</label>
                    <div class="col-lg-12">                        
                        <select class="js-example-basic-single col-sm-12" id="s_target" name="s_target" required>
                            @foreach($targets as $key)
                                <option @if($data->st_id==$key->st_id) selected @endif value="{{$key->st_id}}"> {{$key->st_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-lg-12">  
                        <label for="s_description" id="s_description">{{__('translate.survey_description')}}:</label>
                        <textarea id="s_description" name="s_description" type="text" required placeholder="{{__('translate.survey_description')}}" class="form-control btn-square">{{$data->s_description}}</textarea>
                    </div>
                </div>

                <hr>

                <div class="mb-3 row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="datetime1" id="datetime-label">{{__('translate.start_date')}} :</label>
                                <input type="date" value="{{$data->s_start_date}}" id="s_start_date" name="s_start_date" class="form-control btn-square" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="datetime2" id="datetime-label">{{__('translate.end_date')}} :</label>
                                <input type="date" id="s_end_date" value="{{$data->s_end_date}}" name="s_end_date" class="form-control btn-square" required>
                            </div>
                        </div>
                    </div>
                </div>
<hr>
               
                <div id="questions-container">
                    @foreach($data->questions as $question)
                        <div class="question row mb-3">
                            <label>{{__('translate.question')}} {{$loop->iteration}}:</label>
                            <div class="col-md-6" style="width:70%">
                                <textarea class="form-control" style="height:60%" name="sq_question{{$question->sq_id}}" id="sq_question{{$question->sq_id}}" placeholder="{{__('translate.question')}}{{--  السؤال --}} {{$loop->iteration}}:" required>{{$question->sq_question_text}}</textarea>
                            </div>
                            <div class="col-md-6" style="width:30%">
                                <select class="form-control" style="hieght:100%" name="sq_question_type{{$question->sq_id}}" id="sq_question_type{{$question->sq_id}}"  onchange="addAnswerField(this,{{ $loop->iteration }})">
                                    <option value="paragraph">{{__('translate.options')}}{{--نوع السؤال--}}</option>
                                    <option @if($question->sq_question_type=='paragraph') selected @endif value="paragraph">{{__('translate.paragraph')}}{{--فقرة--}}</option>
                                    <option @if($question->sq_question_type=='short_answer') selected @endif value="short_answer">{{__('translate.short_answer')}}{{--إجابة قصيرة--}}</option>
                                    <option @if($question->sq_question_type=='multiple_choice') selected @endif value="multiple_choice">{{__('translate.multiple_choice')}}{{--اختيار متعدد من متعدد--}}</option>
                                    <option @if($question->sq_question_type=='single_choice') selected @endif value="single_choice">{{__('translate.single_choice')}}{{--اختيار احادي من متعدد--}}</option>
                                </select>
                            </div>

                            @if($question->sq_question_type == 'short_answer')
                                <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                                <div class="answer-container col-md-12" id="answerContainer{{$question->sq_id}}">
                                <input type="text" class="form-control" name="question{{$question->sq_id}}_answer" placeholder="" >
                                </div>
                                @elseif($question->sq_question_type == 'paragraph')
                                <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                                <div class="answer-container col-md-12" id="answerContainer{{$question->sq_id}}">
                                    <textarea class="form-control" name="question{{$question->sq_id}}_answer" placeholder="" ></textarea>
                                </div>
                            @else
                                <input hidden id="questionNumber{{$loop->iteration}}" name="questionNumber{{$loop->iteration}}" value="{{$question->sq_id}}">
                                <div class="answer-container col-md-12" id="answerContainer{{$question->sq_id}}">
                                        <div class="optionPlus">
                                            <label>{{__('translate.options')}}{{--الخيارات--}} :</label> 
                                             <a onclick="addRadioButtonOption({{  $loop->iteration }})">
                                                <i data-feather="plus-square"></i>
                                            </a>
                                        </div>
                                            <div id="radio-options{{ $loop->iteration}}" class="radio-options{{ $loop->iteration}}">
                                            
                                            
  
                                            @foreach($question->options as $option)
                                                    <input type="text" value="{{$option->sqo_option_text}}" required id="q{{ $question->sq_id }}option{{ $loop->iteration }}" class="form-control short_answer" name="q{{ $question->sq_id }}option{{ $option->sqo_id }}">
                                                    @endforeach
                                            </div>
                                          
                                </div>
                            @endif
                            <div class="col trach-toggle" style="width:10%">
                                <a class="q-link" onclick="deleteQuestion({{$question->sq_id}})">
                                    <i data-feather="trash-2"></i>
                                    <span>{{__('translate.delete_question')}}{{-- حذف السؤال --}}</span>
                                </a>
                                <div class="vl"></div>
                                <a class="q-link" onclick="toggleRequired({{$question->sq_id}})">
                                @if($question->sq_question_required == 1 )
                                   <i id="toggleIcon{{$question->sq_id}}"   style="color:red" data-feather="toggle-left" data-state="toggle-left"></i>  <span     style="color:red" id="toggleText{{$question->sq_id}}">{{__('translate.required')}}</span>  
                                   @else <i id="toggleIcon{{$question->sq_id}}"  data-feather="toggle-right" data-state="toggle-right"> </i> <span   id="toggleText{{$question->sq_id}}">{{__('translate.required')}}</span>  
                                   @endif
                                </a>
                            </div>
                            <input hidden id="sq{{$question->sq_id}}_required" name="sq{{$question->sq_id}}_required" value="0">
                        </div>
                 <hr>   
                 @endforeach

                    <input hidden id="questionsNumber" name="questionsNumber" value="{{$data->questions}}" >
                         </div>
                    <div class="f1-buttons">
                        <button type="button" onclick="addQuestion()" class="btn btn-primary">{{__('translate.add_question')}}{{--اضافة سؤال--}}</button>
                        <button type="submit" class="btn btn-primary">{{__('translate.submit')}}{{--حفظ--}}</button>
                    </div>

               
            </div>
        </form>
    </div>
</div>










@endsection


@section('script')
<script>
    let questionCounter = (JSON.parse(document.getElementById("questionsNumber").value).length);
    let optionCounter = 4;
    let updateSurveyForm = document.getElementById("update");
    var titleInput = document.getElementById('s_title');
    var title = document.getElementById('titleh6');

//change title
titleInput.addEventListener('input', function(event) {
    console.log('New value:', event.target.value);
    
    title.textContent = event.target.value;
    if(event.target.value == ""){

        title.textContent='{{__('translate.survey_title')}}';
    }
});

 function addQuestion() {
        const questionsContainer = document.getElementById('questions-container');
        //const questionIndex = questionsContainer.children.length + 1;
        const questionIndex = questionsContainer.getElementsByClassName('question').length + 1;
        questionCounter=questionIndex;
        const questionDiv = document.createElement('div');
        questionDiv.classList.add('question', 'row', 'mb-3');
        questionDiv.innerHTML = `
            <div class="col-md-6" style="width:70%">
                
                <textarea class="form-control" style="height:60%" name="sq_question${questionIndex}" id="sq_question${questionIndex}" placeholder="{{__('translate.question')}}{{--  السؤال --}} ${questionIndex}:" required></textarea>
            </div>
            
            <div class="col-md-6" style="width:30%" >
            
                <select class="form-control" style="hieght:100%"  name="sq_question_type${questionIndex}" id="sq_question_type${questionIndex}" onchange="addAnswerField(this,${questionIndex})">
                    <option value="paragraph">{{__('translate.options')}}{{--نوع السؤال--}}</option>
                    <option value="paragraph">{{__('translate.paragraph')}}{{--فقرة--}}</option>
                    <option value="short_answer">{{__('translate.short_answer')}}{{--إجابة قصيرة--}}</option>
                    <option value="multiple_choice">{{__('translate.multiple_choice')}}{{--اختيار متعدد من متعدد--}}</option>
                    <option value="single_choice">{{__('translate.single_choice')}}{{--اختيار احادي من متعدد--}}</option>
                </select>
            </div>

            <div class="answer-container col-md-12" id="answerContainer${questionIndex}">
            <!-- Answer field will be added dynamically here based on the selected answer type -->
        </div>
            <div class="col trach-toggle" style="width:10%">
            <a class="q-link" onclick="deleteQuestion(${questionIndex})">
                <i data-feather="trash-2"></i>
                <span>{{__('translate.delete_question')}}{{-- حذف السؤال --}}</span>
            </a>
            <div class="vl"></div>
            <a class="q-link" onclick="toggleRequired(${questionIndex})">
                <i id="toggleIcon${questionIndex}" data-feather="toggle-right" data-state="toggle-right"></i>
                <span id="toggleText${questionIndex}">{{__('translate.required')}}</span>
            </a>

        </div>
        <input hidden id="sq${questionIndex}_required" name="sq${questionIndex}_required" value="0">
        <input hidden id="questionNumber${questionIndex}" name="questionNumber${questionIndex}" value="${questionIndex}">

    `;
    
    const hrElement = document.createElement('hr');
    questionsContainer.appendChild(questionDiv);
    questionsContainer.appendChild(hrElement);
    feather.replace(); 
}

function deleteQuestion(questionIndex) {
    console.log("ffff")
    console.log(questionIndex)
    console.log(document.getElementById(`answerContainer${questionIndex}`))
    const questionDiv = document.getElementById(`answerContainer${questionIndex}`).parentNode;
    const hrElement = questionDiv.nextElementSibling; // Get the next element (which is the <hr> tag)
    questionDiv.parentNode.removeChild(questionDiv);
    if (hrElement) {
        hrElement.parentNode.removeChild(hrElement);
    }
    
    questionCounter = questionCounter - 1 ;
}
function toggleRequired(questionIndex) {
    const toggleIcon = document.getElementById(`toggleIcon${questionIndex}`);
    const toggleText = document.getElementById(`toggleText${questionIndex}`);
    const questionRequired = document.getElementById(`sq${questionIndex}_required`);

    // Get the current state from a custom attribute
    const currentState = toggleIcon.getAttribute('data-state');
console.log("vvvvvvvvvv")
console.log(currentState)
    // Set an initial state if it's null (first click)
    const initialState = currentState ;

    // Toggle between 'toggle-left' and 'toggle-right' on every click
    if (initialState === 'toggle-right' ) {
        toggleIcon.setAttribute('data-feather', 'toggle-left');
        toggleIcon.setAttribute('data-state', 'toggle-left');
        toggleIcon.style.color = 'red';
        toggleText.style.color = 'red';
        questionRequired.value=1;
    } else {
        toggleIcon.setAttribute('data-feather', 'toggle-right');
        toggleIcon.setAttribute('data-state', 'toggle-right');
        toggleIcon.style.color = 'black';
        toggleText.style.color = 'black';
        questionRequired.value=0;

    }

    feather.replace(); // Refresh Feather icons after updating dynamically
}



// public/js/survey.js


// public/js/survey.js

function addAnswerField(selectElement,loopIteration) {
    const questionIndex = selectElement.id.replace('sq_question_type', '');
    const answerContainer = document.getElementById(`answerContainer${questionIndex}`);
    const selectedAnswerType = selectElement.value;
console.log(questionIndex)
    // Remove existing answer fields
    while (answerContainer.firstChild) {
        answerContainer.removeChild(answerContainer.firstChild);
    }



    // Add the appropriate input field based on the selected answer type
    switch (selectedAnswerType) {
        case 'paragraph':
            addParagraphField(answerContainer);
            break;
        case 'short_answer':
            addShortAnswerField(answerContainer);
            break;
        case 'checkbox':
            addCheckboxField(answerContainer);
            break;
        case 'multiple_choice':
            addRadioButtonField(answerContainer,questionIndex,loopIteration);
            break;
        case 'single_choice':
            addRadioButtonField(answerContainer,questionIndex,loopIteration);

            break;
        case 'dropdown':
            addDropdownField(answerContainer);
            break;
        case 'file_upload':
            addFileUploadField(answerContainer);
            break;
        case 'time':
            addTimeField(answerContainer);
            break;
        case 'date':
            addDateField(answerContainer);
            break;
        case 'linear_scale':
            addLinearScaleField(answerContainer);
            break;
        case 'multiple_choice_grid':
            addMultipleChoiceGridField(answerContainer);
            break;
        case 'checkbox_grid':
            addCheckboxGridField(answerContainer);
            break;
        default:
            addDefaultStyledInput(answerContainer);
            break;
    }
}

function addParagraphField(answerContainer) {
    const textareaField = document.createElement('textarea');
    textareaField.className = 'form-control paragraph';
    textareaField.name = 'answers[]';
    textareaField.placeholder = '{{__('translate.enter')}}{{--ادخل--}} {{__('translate.paragraph')}}{{--فقرة--}}';
    answerContainer.appendChild(textareaField);
}

function addShortAnswerField(answerContainer) {
    const inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.className = 'form-control short_answer';
    inputField.name = 'answers[]';
    inputField.placeholder = '{{__('translate.enter')}}{{--ادخل--}} {{__('translate.short_answer')}}{{--إجابة قصيرة--}}';
    answerContainer.appendChild(inputField); 
}





function addRadioButtonField(answerContainer,questionIndex,loopIteration) {

    const questionsContainer = document.getElementById('questions-container');
    const add_icon_div = document.createElement('div');
    add_icon_div.classList.add('optionPlus');
    const radioLabel = document.createElement('label');
    radioLabel.textContent = "{{__('translate.options')}}{{--الخيارات--}} :";
    const radioOptions = document.createElement('div');
    radioOptions.id = 'radio-options'+loopIteration;       
    radioOptions.className = 'radio-options'+loopIteration;
    for(i=1 ;i<4 ;i++){
    inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.required = true;
    inputField.id ='q'+ questionIndex +'option'+i;
    inputField.className = 'form-control short_answer';
    inputField.name = 'q'+ questionIndex +'option'+i;
    inputField.placeholder = '{{__('translate.option')}}{{-- الخيار--}} '+ i;
    radioOptions.appendChild(inputField)
    }
    
    const a_element = document.createElement('a');
    const add_icon = document.createElement('i');
    add_icon.setAttribute('data-feather', 'plus-square');
    a_element.addEventListener('click', function() {
    addRadioButtonOption(questionIndex);});    
    a_element.appendChild(add_icon);
    add_icon_div.appendChild(radioLabel);
    add_icon_div.appendChild(a_element);

    answerContainer.appendChild(add_icon_div); 
    answerContainer.appendChild(radioOptions);

    feather.replace(); 
}
function createRadioButtonOption(placeholderText) {
        const radioOption = document.createElement('div');
        radioOption.className = 'radio-option';

        const radioInput = document.createElement('input');
        radioInput.type = 'radio';
        radioInput.name = 'radio-options';
        // Additional attributes or styles for the radio input if needed

        radioOption.appendChild(radioInput);

        return radioOption;
    }
   function getLastOptionNumber(optionId) {
    // Extract the number from the id attribute
    const regex = /(\d+)$/;
    const match = optionId.match(regex);

    if (match) {
        return parseInt(match[0], 10);
    }

    return 0; // Default to 0 if no match
}
    function addRadioButtonOption(questionIndex) {

    const radioOptions = document.getElementById('radio-options'+questionIndex); 
    const lastOption = radioOptions.lastElementChild;

    console.log(lastOption.id);
    console.log(questionIndex);
    console.log("hereeeeeeeee");

    if (lastOption) {
        // Extract the number from the id attribute
        const lastOptionNumber = getLastOptionNumber(lastOption.id);
        console.log('Last Option Number:', lastOptionNumber);

        // Increment the number for the next option
    const newOptionNumber = lastOptionNumber + 1;
    newOptionDiv = document.createElement('div');
    newOptionDiv.classList.add('newOption');
    newOptionDiv.id =`option${newOptionNumber}`;
    newOption = document.createElement('input');
    newOption.required=true;
    newOption.id ='q'+questionIndex+'option'+newOptionNumber;
    newOption.type = 'text';
    newOption.className = 'form-control short_answer';
    newOption.name ='q'+questionIndex+'option'+newOptionNumber;
    newOption.placeholder = '{{__('translate.option')}}{{-- خيار --}} '+ newOptionNumber;
    const del_element = document.createElement('a');
    const delete_icon = document.createElement('i');
    delete_icon.setAttribute('data-feather', 'x-square');
    del_element.addEventListener('click', function() {
    deleteOption(newOptionNumber);});    
    del_element.appendChild(delete_icon);
   

    
    newOptionDiv.appendChild(newOption);
    newOptionDiv.appendChild(del_element);
    radioOptions.appendChild(newOptionDiv);
    feather.replace();
    }
    }


    function deleteOption(optionID) {
        const optionDiv = document.getElementById('option' + optionID);
        optionDiv.remove();
    }


    updateSurveyForm.addEventListener("submit", (e) => {
       // e.preventDefault();
    document.getElementById('questionsNumber').value=questionCounter;
    console.log(JSON.parse(document.getElementById("questionsNumber").value))
    for(i = 1 ; i <= questionCounter ; i++){
        
    const  optionsNumber = document.createElement('div');
    optionsNumber.id='optionsNumber';
    optionsNumber.name='optionsNumber';
    radioOptions = document.getElementById('radio-options'+i);  
    console.log("radioOptions")
    console.log(radioOptions)
    console.log(optionsNumber)
    if (radioOptions!=null) {
    lastOption = radioOptions.lastElementChild; 
   
    console.log(lastOption)
    if (lastOption) {
        console.log("hi")
            // Extract the number from the id attribute
            const lastOptionNumber = radioOptions.querySelectorAll('input').length;
            const questionOptions = document.createElement('input');
            questionOptions.id='q'+i+'optionsNumber';
            questionOptions.name='q'+i+'optionsNumber';
            questionOptions.value=lastOptionNumber;
            questionOptions.hidden=true;
            console.log("here")
            console.log(questionOptions.name);
            //optionsNumber.appendChild(questionOptions); 
            radioOptions.appendChild(questionOptions);
        
        }
    }


    }
});


</script>
@endsection