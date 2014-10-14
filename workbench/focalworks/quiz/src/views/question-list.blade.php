<div class="row">
    <div class="col-md-12"><h1>List Question</h1></div>
</div>
<div ng-controller="question_list">
    <div class="col-md-12">
        <p>Total Grievances: @{{questions.totalCount}}</p>
        <table class="table table-striped table-hover">
            <thead>
                <tr class="info">
                    <th>Question</th>
                    <th>Designation	</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="question in questions">
                    <td>@{{question.qq_text}}</td>
                    <td>@{{question.designation}}</td>
                    <td>
                        <a href="" ng-click="delete_question(question.qq_id,$index)">Delete <i class="glyphicon glyphicon-remove"></i></a>
                        <a href="#/edit/@{{question.qq_id}}" >Edit <i class="glyphicon glyphicon-edit"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>