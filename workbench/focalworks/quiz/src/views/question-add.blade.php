<div class="row">
    <div class="col-md-12"><h1>Add Question</h1></div>
</div>
<div ng-controller="add_question">
    <form method="post" ng-submit="save_form()">
    <div class="row">
        <span class="error-display">@{{ error.global }}</span>
         <div class="col-md-6">

            <div class="form-group">
                <label for="title">Question title</label>
                <input type="text" class="form-control" id="qq_text" placeholder="Question title" name="qq_text" ng-model="question.question[0].qq_text">
                <span class="error-display">@{{error.question}}</span>
            </div>

            <div class="form-group">
                <label for="title">Select Designation</label>
                <?php
                    $values=array_values($designation);
                    $designation=array_combine($values,$values);
                ?>

                   {{Form::select('designation',$designation,'',array('ng-model'=>'question.question[0].designation','class'=>'form-control')) }}
                <span class="error-display">@{{error.designation}}</span>
            </div>

        </div>
        <div class="col-md-6">
            <h3>
                 <label for="title">&nbsp;</label>
                <input type="btn btn-primary btn-md" class="btn btn-primary btn-md" value="Add Option" ng-click="option_add()" />
            </h3>

            <!-- repeat for options  -->
            <div class="form-group col-xs-6"  ng-repeat="option in question.options">
                <label for="title">Option Text</label>
                <a href="" class="float-right fc-red" ng-click="option_remove($index,option.qo_id)" title="Remove Option"><i class="glyphicon glyphicon-remove"></i></a>
                <input type="text" class="form-control" id="qo_text" placeholder="Option Text" ng-model="option.qo_text" />
                <div class="checkbox">
                  <label>

                    <input type="radio" ng-model="option.is_correct" name="correct" ng-value="1" ng-change="onchanging(this)" />
                    This is correct answer.
                  </label>
                </div>
                <span class="error-display">@{{error.option[$index]}}</span>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <input type="submit" name="save" class="btn btn-success btn-md" value="Save" />&nbsp;&nbsp;
            <a href="{{url('script')}}" class="btn btn-primary btn-md">Back</a>
        </div>
    </div>
</form>
</div>
