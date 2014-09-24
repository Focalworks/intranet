<div>
<h2>@{{pageTitle}}</h2>
<div ng-controller="projectCtrl">
    <ul>
        <li ng-repeat="project in projects"><strong>@{{project.project_name}}</strong> :@{{project.board_name}}</li>
        </ul>
</div>
</div>