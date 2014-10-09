@section('content')
<script type="text/javascript" src="{{ asset('packages/focalworks/quiz/js/add_question.js') }}"></script>
<?php
    $qq_id=0;
    if(isset($question[0])) {
        $question=$question[0];
        $qq_id=$question->qq_id;
    }

?>
<div class="row">
    <div class="col-md-12"><h1>Add Question</h1></div>
</div>

{{ Form::open(array('url' => 'quiz/question_save', 'role' => 'form')) }}
<div class="row" ng-app="add_question" ng-controller="add_questionCtrl">
    <input type="hidden" name="qq_id" value="{{$qq_id}}" ng-model="qq_id" />
    <div class="col-md-6">

        <div class="form-group">
            <label for="title">Question title</label>
            <input type="text" class="form-control" id="qq_text" placeholder="Question title" name="qq_text" value="{{Input::old('qq_text')}}">
            <span class="error-display">{{$errors->first('qq_text')}}</span>
        </div>

    </div>
    <div class="col-md-6">
        <h3>
             <label for="title">&nbsp;</label>
            <input type="btn btn-primary btn-md" class="btn btn-primary btn-md" value="Add Option" ng-click="add_filed()" />
        </h3>

        <!-- repeat for options  -->
        <div class="form-group col-xs-6"  ng-repeat="option in options">
            <label for="title">Option Text</label>
            <input type="text" class="form-control" id="qo_text" placeholder="Option Text" name="qo_text[@{{option.id}}]" value="" />
            <div class="checkbox">
              <label>
                <input type="radio" name="correct" value="@{{option.id}}" />
                This is correct answer.
              </label>
            </div>
            <span class="error-display">{{$errors->first('qo_text')}}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <input type="submit" name="save" class="btn btn-success btn-md" value="Save" />&nbsp;&nbsp;
        <a href="{{url('grievance/list')}}" class="btn btn-primary btn-md">Back</a>
    </div>
</div>
{{ Form::close() }}

@stop