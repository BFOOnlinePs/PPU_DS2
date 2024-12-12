<select class="js-example-basic-single col-sm-12" multiple="multiple" id="selectedCourses" multiple>
    @foreach($course as $key)
    <option value="{{$key->c_id}}">{{$key->c_name}}</option>
    @endforeach
</select>
