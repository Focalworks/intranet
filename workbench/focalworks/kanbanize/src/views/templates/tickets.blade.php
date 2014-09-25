<h2>@{{pageTitle}}</h2>
<table class="table table-bordered table-striped table-hover" id="sortable" ng-controller="ticketCtrl">
 	<thead>
 		<tr>
 		  <th>#</th>
 			<th>Task</th>
 			<th>Assignee</th>
 			<th>Type</th>
 			<th>Description</th>
 		</tr>
 	</thead>

 	<tbody>
 		<tr ng-repeat="ticket in tickets">
      <td>@{{$index + 1}}</td>
 			<td>@{{ticket.title}}</td>
 			<td>@{{ticket.assignee}}</td>
 			<td>@{{ticket.type}}</td>
 			<td>@{{ticket.description}}</td>
 		</tr>
 	</tbody>
 </table>