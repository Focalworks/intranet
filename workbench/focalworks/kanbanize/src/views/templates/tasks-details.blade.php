
<h2>@{{pageTitle}}</h2>
<table class="table table-bordered table-striped table-hover" ng-controller="taskCtrl">
 	<thead>
 		<tr>
 		  <th>Task ID</th>
 			<th>Task</th>
 			<th>Assignee</th>
 			<th>Type</th>
 			<th>Description</th>
 			<th>time</th>
 		</tr>
 	</thead>

  <tbody>
 		<tr ng-repeat="task in taskLists">
      <td>@{{ task }}</td>
 			<td></td>
 			<td></td>
 			<td></td>
 			<td></td>
 		</tr>
 	</tbody>
</table>