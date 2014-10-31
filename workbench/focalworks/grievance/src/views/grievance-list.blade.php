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
    <div class="col-md-12"><h2>List of Grievances / Suggestions</h2></div>
</div>
<div class="cards-view">
<div class="row">
    <div class="col-md-12">
        <p><a href="{{url('grievance/add')}}" class="btn btn-success btn-md"><span class="glyphicon glyphicon-plus"></span>&nbsp;ADD NEW</a></p>
    </div>
</div>
@if ($grievanceCount <= 0)
<h2>You have not submitted an Grievances or suggestions. To add click {{link_to('grievance/add', 'here')}}</h2>
@else
<div class="filter-container clearfix">
    {{ Form::open(array('url' => 'grievance/filter', 'role' => 'form', 'class' => 'form-inline')) }}
    <div class="col-lg-3 col-md-4 col-sm-4">
        <div class="form-group">
            <label class="">Category</label><br />
            @if(isset($filters['category']))
            {{GlobalHelper::getDropdownFromArray('category', Grievance::getGrievanceCategories(), $filters['category'])}}
            @else
            {{GlobalHelper::getDropdownFromArray('category', Grievance::getGrievanceCategories())}}
            @endif
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-4">
        <div class="form-group">
            <label class="">Urgency</label><br />
            @if(isset($filters['urgency']))
            {{GlobalHelper::getDropdownFromArray('urgency', Grievance::getUrgencies(), $filters['urgency'])}}
            @else
            {{GlobalHelper::getDropdownFromArray('urgency', Grievance::getUrgencies())}}
            @endif
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-4">
        <div class="form-group">
            <label class="">Status</label><br />
            @if(isset($filters['status']))
            {{GlobalHelper::getDropdownFromArray('status', Grievance::getStatusName(), $filters['status'])}}
            @else
            {{GlobalHelper::getDropdownFromArray('status', Grievance::getStatusName())}}
            @endif
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-5">
        <div class="form-group">
           <label class="">&nbsp;</label><br />
            <button class="btn btn-primary"><span class="glyphicon glyphicon-filter"></span>&nbsp;Filter</button>
            @if(isset($userObj->grievanceFilter))
              <a href="{{url('grievance/reset')}}" class="btn btn-primary"><span class="glyphicon glyphicon-repeat"></span>&nbsp;Reset</a>
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
                    <th></th>
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
                    <td>{{link_to('grievance/readonly/' . $grievance->id,  $grievance->title )}}
                    <br>
                    <span>{{substr(strip_tags($grievance->description),0,100)}}</span>
                    @if ($access)
                        @if(isset($grievance->status) && ($grievance->status==3) && isset($grievance->req_reopen))
                            <a href="#" class="mytest" data-toggle="tooltip" data-placement='right' title="{{$grievance->req_reopen}}"><span class="text-danger glyphicon glyphicon-info-sign"></span></a>
                        @endif
                    @endif
                    </td>
                    <td class="col-md-1">
                    @if($grievance->user_id==$my_user_id)
                        @if($grievance->status==1)
                            <a href="view/{{$grievance->id}}" data-toggle="tooltip" data-placement='right' title="Edit" class="mytest"><span class="glyphicon glyphicon-edit" ></span></a>
                        @endif
                    @endif
                    </td>
                    <td class="col-md-1">{{ucwords($grievance->category)}}</td>
                    <td class="col-md-1">{{ucwords(Grievance::getUrgencies($grievance->urgency))}}</td>
                    <td class="col-md-1">{{GlobalHelper::formatDate($grievance->created_at, 'dS M Y')}}</td>
                    <td class="col-md-1">{{Grievance::getStatusName($grievance->status)}}</td>
                    @if ($access)
                    <td class="col-md-1">{{Grievance::getUserName($grievance->user_id,$grievance->anonymous)}}</td>
                    <td class="col-md-1"> 

                    {{link_to('grievance/manage/' . $grievance->id, '')}}
                     <a href="view/{{$grievance->id}}" data-toggle="tooltip" data-placement='right' title="Manage" class="mytest"><span class="glyphicon glyphicon-briefcase" ></span></a>
                     &nbsp;&nbsp;&nbsp;

                    {{link_to('grievance/list', '', array('class' => 'delete-link glyphicon glyphicon-remove',
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