<div class="row">
    <div class="col-md-12"><h1>List Question</h1></div>
</div>
<div ng-controller="exam_list">
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
                        <a href="" ng-click="view_exam($index)" >View <i class="glyphicon glyphicon-eye-open"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>