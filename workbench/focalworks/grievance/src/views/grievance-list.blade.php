@section('scripts')
@parent
<script src="//cdn.ckeditor.com/4.4.3/basic/ckeditor.js"></script>
<script type="text/javascript">
$(function() {
    $('.mytest').tooltip();
    $('.confirm').click(function(){
        return confirm("Sure you want to delete ?");
    });
    $( "#addnew" ).click(function() {
        $( "#div_addnew" ).show();
         $( "#addnew" ).hide();
    });
});
</script>
@stop
@section('content')
<div class="row">
    <div class="col-md-12"><h2>List of Grievances / Suggestions</h2></div>
</div>
<div class="cards-view">
<div class="row">
    <div class="col-md-12 ">
        <button id="addnew" class="btn btn-success btn-md"><span class="glyphicon glyphicon-plus"></span>&nbsp;ADD NEW</button>
    </div>
    <div style="display: none" id="div_addnew" class="col-md-12 ">
        <?php
$urgency = Input::old('urgency');
if ($urgency == '') {
    $urgency = 1;
}
?>

{{ Form::open(array('url' => 'grievance/save', 'role' => 'form', 'files' => true)) }}
<div class="row">
    <div class="col-lg-4 col-md-5">
        <h2>Add Grievance</h2>
    </div>
    <div class="col-lg-4 col-md-5">
        <div class="btn-wrap">
            <button type="submit" class="btn btn-success btn-md"><span class="glyphicon glyphicon-floppy-save"></span>&nbsp; Save</button>
        </div>
    </div>
</div>
<div class="row">
        <div class="col-lg-8 col-md-10">
            <div class="cards-view">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" placeholder="" name="title" value="{{Input::old('title')}}">
                            <span class="error-display">{{$errors->first('title')}}</span>
                        </div>
                        
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea name="body" id="body" class="form-control">{{Input::old('body')}}</textarea>
                            <span class="error-display">{{$errors->first('body')}}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category">Category</label>
                            {{GlobalHelper::getDropdownFromArray('category', Grievance::getGrievanceCategories(), Input::old('category'))}}
                            <span class="error-display">{{$errors->first('category')}}</span>
                        </div>
                        
                        <div class="form-group">
                            <label for="urgency">Urgency</label>
                            {{GlobalHelper::getDropdownFromArray('urgency', Grievance::getUrgencies(), $urgency)}}
                            <span class="error-display">{{$errors->first('urgency')}}</span>
                        </div>
                    
                        <div class="form-group">
                            <label for="urgency">Anonymous</label> &nbsp;
                            <input type="checkbox" name="anonymous" id="anonymous" value="1" >
                        </div>   
                        <div class="form-group upload-img-wrapper">
                            <label for="photo">Photo if any</label>
                            <div class="upload-img">
                                <input type="file" class="form-input custom-file-input" placeholder="Photo" name="photo">
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

{{ Form::close() }}
<script>
    $(window).load(function ()
    {
        CKEDITOR.replace('body');
    });
</script>
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
    <div class="col-lg-12 col-md-12">
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
                    <th>User</th>
                    <th></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($grievances as $grievance)
                <tr>
                    <td class="col-lg-3 col-md-3">{{link_to('grievance/readonly/' . $grievance->id,  $grievance->title )}}
                     @if ($access)
                        @if(isset($grievance->status) && ($grievance->status==3) && isset($grievance->req_reopen))
                            <a href="#" class="mytest" data-toggle="tooltip" data-placement='right' title="{{$grievance->req_reopen}}"><span class="text-danger glyphicon glyphicon-info-sign"></span></a>
                        @endif
                    @endif
                    <br>
                    <span>{{substr(strip_tags($grievance->description),0,60)}}</span>
                    </td>
                    <td class="">
                    @if($grievance->user_id==$my_user_id)
                        @if($grievance->status==1)
                            <a href="view/{{$grievance->id}}" data-toggle="tooltip" data-placement='right' title="Edit" class="mytest"><span class="glyphicon glyphicon-edit" ></span></a>
                        @endif
                    @endif
                    </td>
                    <td class="">{{ucwords($grievance->category)}}</td>
                    <td class="">{{ucwords(Grievance::getUrgencies($grievance->urgency))}}</td>
                    <td class="">{{GlobalHelper::formatDate($grievance->created_at, 'dS M Y')}}</td>
                    <td class="">{{Grievance::getStatusName($grievance->status)}}</td>
                    @if ($access)
                    <td class="">{{Grievance::getUserName($grievance->user_id,$grievance->anonymous)}}</td>
                    <td class=""> 
                     <a href="manage/{{$grievance->id}}" data-toggle="tooltip" data-placement='left' title="Manage" class="mytest"><span class="glyphicon glyphicon-briefcase" ></span></a>
                     &nbsp;&nbsp;&nbsp;
                     <a href="delete/{{$grievance->id}}" data-toggle="tooltip" data-placement='left' title="Delete" class="mytest confirm"><span class="glyphicon glyphicon-remove" ></span>
                     </a>
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