<div class="row">
    <div class="col-md-12"><h1>List Question</h1></div>
</div>
<div ng-controller="exam_view">
    <div class="col-md-12">
        <p>Exams</p>
        <table class="table table-striped table-hover">
            <thead>
                <tr class="info">
                    <th>Candidate</th>
                    <th>Email</th>
                    <th>Mobile No.</th>
                    <th>Designation</th>
                    <th>Attend On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="exam in exams">
                    <td>@{{exam.qu_fname}}</td>
                    <td>@{{exam.qu_email}}</td>
                    <td>@{{exam.qu_mobile}}</td>
                    <td>@{{exam.qu_designation}}</td>
                    <td>@{{exam.created}}</td>
                    <td>
                        <a href="#/examList/@{{exam.qe_id}}" >View <i class="glyphicon glyphicon-eye-open"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="form-group" ng-repeat="question in questions">
                <label for="exampleInputEmail1">{{question.qq_text}}</label>
                <div class="radio" ng-repeat="option in question.options">
                  <label>
                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">@{{option.qo_text}}
                  </label>
                </div>
            </div>
        </div>

    </div>

</div>