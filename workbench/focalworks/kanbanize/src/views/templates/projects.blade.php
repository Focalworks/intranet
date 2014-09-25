<div>
<h2>@{{pageTitle}}</h2>
<div ng-controller="projectCtrl">
    <ul>
        <li ng-repeat="project in projects">
        <strong>
        @{{project.project_name}}</strong> :
        <a href="kanbanize#/kanban/@{{ project.board_id }}/tickets">@{{project.board_name}}</a></li>
        </ul>
</div>
</div>