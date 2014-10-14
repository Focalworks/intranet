@section('scripts')
@parent
<script type="text/javascript">
    $(function() {
    $('.mytest').tooltip();
});
</script>
@stop
@section('content')
<div class="row">
    <div class="col-md-12"><h1>List of Grievances / Suggestions</h1></div>
</div>
<div class="row">
    <div class="col-md-12">
        <p><a href="{{url('grievance/add')}}" class="btn btn-success btn-md">+ Add New</a></p>
    </div>
</div>
@if ($grievanceCount <= 0)
<h2>You have not submitted an Grievances or suggestions. To add click {{link_to('grievance/add', 'here')}}</h2>
@else
<div class="filter-container clearfix">
    {{ Form::open(array('url' => 'grievance/filter', 'role' => 'form', 'class' => 'form-inline')) }}
    <div class="col-lg-2 col-md-2 col-sm-4">
        <div class="form-group">
            <label class="">Category</label><br />
            @if(isset($filters['category']))
            {{GlobalHelper::getDropdownFromArray('category', Grievance::getGrievanceCategories(), $filters['category'])}}
            @else
            {{GlobalHelper::getDropdownFromArray('category', Grievance::getGrievanceCategories())}}
            @endif
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-4">
        <div class="form-group">
            <label class="">Urgency</label><br />
            @if(isset($filters['urgency']))
            {{GlobalHelper::getDropdownFromArray('urgency', Grievance::getUrgencies(), $filters['urgency'])}}
            @else
            {{GlobalHelper::getDropdownFromArray('urgency', Grievance::getUrgencies())}}
            @endif
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-4">
        <div class="form-group">
            <label class="">Status</label><br />
            @if(isset($filters['status']))
            {{GlobalHelper::getDropdownFromArray('status', Grievance::getStatusName(), $filters['status'])}}
            @else
            {{GlobalHelper::getDropdownFromArray('status', Grievance::getStatusName())}}
            @endif
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="form-group">
           <label class="">&nbsp;</label><br />
            <button class="btn btn-primary">Filter</button>
            @if(isset($userObj->grievanceFilter))
              <a href="{{url('grievance/reset')}}" class="btn btn-primary">Reset</a>
            @endif
        </div>
    </div>
    {{Form::close()}}
</div><br />
@if (isset($grievances) && count($grievances) > 0)
<div class="row">
    <div class="col-md-12">
        <p>Total Grievances: {{$grievanceCount}}</p>
        <table class="table table-striped table-hover">
            <thead>
                <tr class="info">
                    <th>Title</th>
                    <th>{{Grievance::sortColumnLinkHelper($sortArray, 'category', $sortBy)}}</th>
                    <th>{{Grievance::sortColumnLinkHelper($sortArray, 'urgency', $sortBy)}}</th>
                    <th>{{Grievance::sortColumnLinkHelper($sortArray, 'created_at', $sortBy)}}</th>
                    <th>{{Grievance::sortColumnLinkHelper($sortArray, 'status', $sortBy)}}</th>
                    @if ($access)
                    <th>Created User</th>
                    <th></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($grievances as $grievance)
                <tr>
                    @if ($access)
                        <td>
                            {{link_to('grievance/manage/' . $grievance->id, $grievance->title." (".substr(strip_tags($grievance->description),0,10)."... )" )}}
                            @if(isset($grievance->status) && ($grievance->status==3) && isset($grievance->req_reopen))
                                <u><i><a href="#" class="mytest" data-toggle="tooltip" data-placement='bottom' title="{{$grievance->req_reopen}}">Request to reopen</a></i></u>
                            @endif
                        </td>
                    @else
                        <td>{{link_to('grievance/view/' . $grievance->id,  $grievance->title." (".substr(strip_tags($grievance->description),0,10)."... )" )}}</td>
                    @endif
                    <td class="col-md-2">{{ucwords($grievance->category)}}</td>
                    <td class="col-md-2">{{ucwords(Grievance::getUrgencies($grievance->urgency))}}</td>
                    <td class="col-md-2">{{GlobalHelper::formatDate($grievance->created_at, 'dS M Y')}}</td>
                    <td class="col-md-2">{{Grievance::getStatusName($grievance->status)}}</td>
                    @if ($access)
                    <td>{{Grievance::getUserName($grievance->user_id,$grievance->anonymous)}}</td>
                    <td> 
                    {{link_to('grievance/list', 'Delete', array('class' => 'delete-link',
                        'data-delete-id' => $grievance->id,
                        'data-delete-entity' => GRIEVANCE))}}
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
    <!--h2>No records found.</h2-->
@endif

@if ($sort)
{{$grievances->appends($sort)->links()}}
@else
{{$grievances->links()}}
@endif

@endif


@stop