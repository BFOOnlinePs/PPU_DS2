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
<div class="survey-form-container" >
                    <div class="card card-absolute"  >
                          <div class="card-header bg-primary" >
                            <h5 id="titleh6" class="text-white">{{__('translate.survey_title')}} {{-- عنوان الاستبيان --}}</h5>
                          </div>

  
    <form class="form-horizontal" id="createSurvey" method="POST"  action="{{ route('admin.survey.createSurvey') }}">
    @csrf

                          <div class="card-body">
<!-- Form Name -->


<div class="mb-3 row">
  <div class="col-lg-12">
    <label for="s_title1" id="s_title1">{{__('translate.survey_title')}}:</label>
    <input id="s_title" name="s_title" type="text" required placeholder="{{__('translate.survey_title')}} " class="form-control btn-square input-md">
  </div>
</div>
<div class="mb-3 row">
  <div class="col-lg-12">
   <label for="s_target" id="s_target">{{__('translate.target_group')}}{{--  الفئة المستهدفة --}}:</label>
   <select class="js-example-basic-single col-sm-12" id="s_target" name="s_target" required>
   @foreach($targets as $key)
   <option value="{{$key->st_id}}">{{__('translate.target_group')}}{{--  الفئة المستهدفة --}} : {{$key->st_name}}</option>
   @endforeach
    </select>    
  </div>
</div>


<div class="mb-3 row">
  <div class="col-lg-12">
    <label for="s_description" id="s_description">{{__('translate.survey_description')}}:</label>
    <textarea id="s_description" name="s_description" type="text" required placeholder="{{__('translate.survey_description')}}" class="form-control btn-square"></textarea>
  </div>
</div>
<hr>
<div class="mb-3 row">
  <div class="col-lg-12">
   
    <div class="row">
      <div class="col-lg-6">
         <label for="datetime1" id="datetime-label">{{__('translate.start_date')}} :</label>
        <input type="date" id="s_start_date" name="s_start_date" class="form-control btn-square" required>
      </div>
      <div class="col-lg-6">
      <label for="datetime1" id="datetime-label">{{__('translate.end_date')}} :</label>
        <input type="date" id="s_end_date" name="s_end_date" class="form-control btn-square" required>
      </div>
    </div>
  </div>
</div>

<hr>

<div id="questions-container" >
    
</div>
<div class="f1-buttons">              
                <button type="button" onclick="addQuestion()" class="btn btn-primary">{{__('translate.add_question')}}{{--اضافة سؤال--}}</button>
                <button type="submit" class="btn btn-primary">{{__('translate.submit')}}{{--حفظ--}}</button>    
            </div>
</div>
<input hidden id="questionsNumber" name="questionsNumber">

</form>


</fieldset>
</div>
</div>
    
  









@endsection


@section('script')
<script>
    let questionCounter = 0;
    let optionCounter = 4;
    let addsurveyForm = document.getElementById("createSurvey");
    var titleInput = document.getElementById('s_title');
    var title = document.getElementById('titleh6');

// Add an input event listener
titleInput.addEventListener('input', function(event) {
    console.log('New value:', event.target.value);
    
    title.textContent = event.target.value;
    if(event.target.value == ""){

        title.textContent='{{__('translate.survey_title')}}';
    }
    // Add your custom logic here
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
            <!--   <label>{{__('translate.question')}} ${questionIndex}:</label>-->
                <textarea class="form-control" style="height:60%" name="sq_question${questionIndex}" id="sq_question${questionIndex}" placeholder="{{__('translate.question')}}{{--  السؤال --}} ${questionIndex}:" required></textarea>
            </div>
            
            <div class="col-md-6" style="width:30%" >
            
                <select class="form-control" style="hieght:100%"  name="sq_question_type${questionIndex}" id="sq_question_type${questionIndex}" onchange="addAnswerField(this)">
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
                <i id="toggleIcon${questionIndex}" data-feather="toggle-right"></i>
                <span id="toggleText${questionIndex}">{{__('translate.required')}}</span>
            </a>

        </div>
        <input hidden id="sq${questionIndex}_required" name="sq${questionIndex}_required" value="0">

    `;
    
    const hrElement = document.createElement('hr');
    questionsContainer.appendChild(questionDiv);
    questionsContainer.appendChild(hrElement);
    feather.replace(); 
}

function deleteOption(optionID) {
        const optionDiv = document.getElementById('option' + optionID);
        optionDiv.remove();
    }
function deleteQuestion(questionIndex) {

    const questionDiv = document.getElementById(`answerContainer${questionIndex}`).parentNode;
    const hrElement = questionDiv.nextElementSibling; // Get the next element (which is the <hr> tag)
    questionDiv.parentNode.removeChild(questionDiv);
    if (hrElement) {
        hrElement.parentNode.removeChild(hrElement);
        questionCounter=questionCounter-1;
    }
}
function toggleRequired(questionIndex) {
    const toggleIcon = document.getElementById(`toggleIcon${questionIndex}`);
    const toggleText = document.getElementById(`toggleText${questionIndex}`);
    const questionRequired = document.getElementById(`sq${questionIndex}_required`);

    // Get the current state from a custom attribute
    const currentState = toggleIcon.getAttribute('data-state');

    // Set an initial state if it's null (first click)
    const initialState = currentState || 'toggle-right';

    // Toggle between 'toggle-left' and 'toggle-right' on every click
    if (initialState === 'toggle-right') {
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

function addAnswerField(selectElement) {
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
            addRadioButtonField(answerContainer,questionIndex);
            break;
        case 'single_choice':
            addRadioButtonField(answerContainer,questionIndex);

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

function addCheckboxField(answerContainer) {
    const checkboxLabel = document.createElement('label');
    checkboxLabel.textContent = 'Checkbox Options:';

    const checkboxOptions = document.createElement('div');
    checkboxOptions.className = 'checkbox-options';

    const option1 = createCheckboxOption('{{__('translate.option')}}{{-- الخيار--}} 1');
    const option2 = createCheckboxOption('{{__('translate.option')}}{{-- الخيار--}} 2');
    const option3 = createCheckboxOption('{{__('translate.option')}}{{-- الخيار--}} 3');

    checkboxOptions.appendChild(option1);
    checkboxOptions.appendChild(option2);
    checkboxOptions.appendChild(option3);

    answerContainer.appendChild(checkboxLabel);
    answerContainer.appendChild(checkboxOptions);
}

function createCheckboxOption(labelText) {
    const optionDiv = document.createElement('div');
    const checkbox = document.createElement('input');
    const label = document.createElement('label');

    checkbox.type = 'checkbox';
    checkbox.name = 'answers[]';
    label.textContent = labelText;

    optionDiv.appendChild(checkbox);
    optionDiv.appendChild(label);

    return optionDiv;
}

function addRadioButtonField(answerContainer,questionIndex) {
   
    const questionsContainer = document.getElementById('questions-container');
    const add_icon_div = document.createElement('div');
    add_icon_div.classList.add('optionPlus');
    const radioLabel = document.createElement('label');
    radioLabel.textContent = "{{__('translate.options')}}{{--الخيارات--}} :";
    const radioOptions = document.createElement('div');
    radioOptions.id = 'radio-options'+questionIndex;
    radioOptions.className = 'radio-options'+questionIndex;
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
    radioOptions.appendChild(newOption);
    newOptionDiv.appendChild(newOption);
    newOptionDiv.appendChild(del_element);
    radioOptions.appendChild(newOptionDiv);

    feather.replace();
    }
    }
function createRadioButtonOption(labelText) {
    const optionDiv = document.createElement('div');
    const radioButton = document.createElement('input');
    const label = document.createElement('label');

    radioButton.type = 'radio';
    radioButton.name = 'answers[]';
    label.textContent = labelText;

    optionDiv.appendChild(radioButton);
    optionDiv.appendChild(label);

    return optionDiv;
}

function addMultipleChoiceField(answerContainer) {
    // Similar to radio button and checkbox, create multiple choice options
    const mcLabel = document.createElement('label');
    mcLabel.textContent = 'Multiple Choice Options:';

    const mcOptions = document.createElement('div');
    mcOptions.className = 'mc-options';

    const option1 = createMultipleChoiceOption('Option 1');
    const option2 = createMultipleChoiceOption('Option 2');
    const option3 = createMultipleChoiceOption('Option 3');

    mcOptions.appendChild(option1);
    mcOptions.appendChild(option2);
    mcOptions.appendChild(option3);

    answerContainer.appendChild(mcLabel);
    answerContainer.appendChild(mcOptions);
}

function createMultipleChoiceOption(labelText) {
    const optionDiv = document.createElement('div');
    const radio = document.createElement('input');
    const label = document.createElement('label');

    radio.type = 'radio';
    radio.name = 'answers[]';
    label.textContent = labelText;

    optionDiv.appendChild(radio);
    optionDiv.appendChild(label);

    return optionDiv;
}

function addDropdownField(answerContainer) {
    const dropdownLabel = document.createElement('label');
    dropdownLabel.textContent = 'Dropdown Options:';

    const dropdown = document.createElement('select');
    dropdown.className = 'form-control dropdown';
    dropdown.name = 'answers[]';

    const option1 = createDropdownOption('Option 1');
    const option2 = createDropdownOption('Option 2');
    const option3 = createDropdownOption('Option 3');

    dropdown.appendChild(option1);
    dropdown.appendChild(option2);
    dropdown.appendChild(option3);

    answerContainer.appendChild(dropdownLabel);
    answerContainer.appendChild(dropdown);
}

function createDropdownOption(labelText) {
    const option = document.createElement('option');
    option.value = labelText;
    option.textContent = labelText;
    return option;
}

function addFileUploadField(answerContainer) {
    const fileUploadLabel = document.createElement('label');
    fileUploadLabel.textContent = 'File Upload:';

    const fileUpload = document.createElement('input');
    fileUpload.type = 'file';
    fileUpload.className = 'form-control file-upload';
    fileUpload.name = 'answers[]';

    answerContainer.appendChild(fileUploadLabel);
    answerContainer.appendChild(fileUpload);
}

function addTimeField(answerContainer) {
    const timeLabel = document.createElement('label');
    timeLabel.textContent = 'Time:';

    const timeInput = document.createElement('input');
    timeInput.type = 'time';
    timeInput.className = 'form-control time';
    timeInput.name = 'answers[]';

    answerContainer.appendChild(timeLabel);
    answerContainer.appendChild(timeInput);
}

function addDateField(answerContainer) {
    const dateLabel = document.createElement('label');
    dateLabel.textContent = 'Date:';

    const dateInput = document.createElement('input');
    dateInput.type = 'date';
    dateInput.className = 'form-control date';
    dateInput.name = 'answers[]';

    answerContainer.appendChild(dateLabel);
    answerContainer.appendChild(dateInput);
}

function addLinearScaleField(answerContainer) {
    const linearScaleLabel = document.createElement('label');
    linearScaleLabel.textContent = 'Linear Scale:';

    const linearScaleInput = document.createElement('input');
    linearScaleInput.type = 'range';
    linearScaleInput.className = 'form-control linear-scale';
    linearScaleInput.name = 'answers[]';

    answerContainer.appendChild(linearScaleLabel);
    answerContainer.appendChild(linearScaleInput);
}

function addMultipleChoiceGridField(answerContainer) {
    const mcGridLabel = document.createElement('label');
    mcGridLabel.textContent = 'Multiple Choice Grid:';

    const mcGridOptions = document.createElement('div');
    mcGridOptions.className = 'mc-grid-options';

    const row1 = createMCGridRow('Row 1');
    const row2 = createMCGridRow('Row 2');
    const row3 = createMCGridRow('Row 3');

    mcGridOptions.appendChild(row1);
    mcGridOptions.appendChild(row2);
    mcGridOptions.appendChild(row3);

    answerContainer.appendChild(mcGridLabel);
    answerContainer.appendChild(mcGridOptions);
}

function createMCGridRow(rowLabel) {
    const rowDiv = document.createElement('div');
    rowDiv.className = 'mc-grid-row';

    const label = document.createElement('label');
    label.textContent = rowLabel;
    rowDiv.appendChild(label);

    const option1 = createCheckboxOption('Option 1');
    const option2 = createCheckboxOption('Option 2');
    const option3 = createCheckboxOption('Option 3');

    rowDiv.appendChild(option1);
    rowDiv.appendChild(option2);
    rowDiv.appendChild(option3);

    return rowDiv;
}

function addCheckboxGridField(answerContainer) {
    const checkboxGridLabel = document.createElement('label');
    checkboxGridLabel.textContent = 'Checkbox Grid:';

    const checkboxGridOptions = document.createElement('div');
    checkboxGridOptions.className = 'checkbox-grid-options';

    const row1 = createCheckboxGridRow('Row 1');
    const row2 = createCheckboxGridRow('Row 2');
    const row3 = createCheckboxGridRow('Row 3');

    checkboxGridOptions.appendChild(row1);
    checkboxGridOptions.appendChild(row2);
    checkboxGridOptions.appendChild(row3);

    answerContainer.appendChild(checkboxGridLabel);
    answerContainer.appendChild(checkboxGridOptions);
}

function createCheckboxGridRow(rowLabel) {
    const rowDiv = document.createElement('div');
    rowDiv.className = 'checkbox-grid-row';

    const label = document.createElement('label');
    label.textContent = rowLabel;
    rowDiv.appendChild(label);

    const option1 = createCheckboxOption('Option 1');
    const option2 = createCheckboxOption('Option 2');
    const option3 = createCheckboxOption('Option 3');

    rowDiv.appendChild(option1);
    rowDiv.appendChild(option2);
    rowDiv.appendChild(option3);

    return rowDiv;
}

function addDefaultStyledInput(answerContainer) {
    const inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.className = 'form-control';
    inputField.name = 'answers[]';
    inputField.placeholder = 'Enter answer';
    answerContainer.appendChild(inputField);
}
    addsurveyForm.addEventListener("submit", (e) => {

    document.getElementById('questionsNumber').value=questionCounter;
    for(i = 1 ; i <= questionCounter ; i++){
    const answerContainer = document.getElementById(`answerContainer${i}`);
    radioOptions = document.getElementById('radio-options'+i); 
    if (radioOptions) {
    lastOption = radioOptions.lastElementChild; 
    if (lastOption) {
            // Extract the number from the id attribute
            const lastOptionNumber = getLastOptionNumber(lastOption.id);
            const questionOptions = document.createElement('input');
            questionOptions.id='q'+i+'optionsNumber';
            questionOptions.name='q'+i+'optionsNumber';
            questionOptions.value=lastOptionNumber;
            questionOptions.hidden=true;
            answerContainer.appendChild(questionOptions); 

        
        }
    }


    }
});

// addsurveyForm.addEventListener("submit", (e) => {
//     data = $('#createSurvey').serialize();
//     console.log("hi")
//     console.log(data)
//     var csrfToken = $('meta[name="csrf-token"]').attr('content');
//     // Send an AJAX request with the CSRF token
   

//     // Send an AJAX request
//     $.ajax({
//         beforeSend: function(){
//             $('#LoadingModal').modal('show');
//         },
//         type: 'POST',
//         url: "{{ route('admin.survey.createSurvey') }}",
//         data: data,
//         success: function(response) {
// alert('jjjjj')
//             //to show the search area if the added course is the first one
//             //because it's hidden when the table is empty
      

//         },
//         error: function(xhr, status, error) {
//             console.error(xhr.responseText);
//         }
//     });

//     });
</script>
@endsection