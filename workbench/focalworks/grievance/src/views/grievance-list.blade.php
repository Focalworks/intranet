@section('scripts')
@parent<script src="//cdn.ckeditor.com/4.4.3/basic/ckeditor.js"></script>
@stop
@section('content')
<div class="row">
    <div class="col-md-12"><h1>List of Grievances / Suggestions</h1></div>
</div>

<div class="row">
    <div class="col-md-12">
        <p><a href="{{url('grievance/add')}}" class="btn btn-primary btn-md">+ Add New</a></p>
    </div>
</div>

@if (isset($grievances) && count($grievances) > 0)
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-hover">
            <thead>
                <tr class="info">
                	<th>Title</th>
                    <th>{{Grievance::sortColumnLinkHelper($sortArray, 'category', $sortBy)}}</th>
                    <th>{{Grievance::sortColumnLinkHelper($sortArray, 'urgency', $sortBy)}}</th>
                    <th>{{Grievance::sortColumnLinkHelper($sortArray, 'created_at', $sortBy)}}</th>
                    <th>{{Grievance::sortColumnLinkHelper($sortArray, 'status', $sortBy)}}</th>
                	@if ($access)
                	<th></th>
                	@endif
                </tr>
            </thead>
            <tbody>
                @foreach($grievances as $grievance)
                <tr>
                	<td>{{link_to('grievance/view/' . $grievance->id, $grievance->title)}}</td>
                	<td class="col-md-2">{{ucwords($grievance->category)}}</td>
                	<td class="col-md-2">{{ucwords(Grievance::getUrgencies($grievance->urgency))}}</td>
                	<td class="col-md-2">{{GlobalHelper::formatDate($grievance->created_at, 'dS M Y')}}</td>
                	<td class="col-md-2">{{Grievance::getStatusName($grievance->status)}}</td>
                	@if ($access)
                	<th>
                	{{link_to('grievance/manage/' . $grievance->id, 'Manage')}} / 
                	{{link_to('grievance/list', 'Delete', array('class' => 'delete-link',
                        'data-delete-id' => $grievance->id,
                        'data-delete-entity' => GRIEVANCE))}}
                	</th>
                	@endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
    <h2>You have not submitted an Grievances or suggestions. To add click {{link_to('grievance/add', 'here')}}</h2>
@endif

@if ($sort)
{{$grievances->appends($sort)->links()}}
@else
{{$grievances->links()}}
@endif

@stop