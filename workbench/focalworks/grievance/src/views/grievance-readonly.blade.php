@section('scripts')
@parent 
<script type="text/javascript" src="{{ asset('js/dev/comment/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dev/comment/add_comments.js') }}"></script>
<style type="text/css">
    #myModal {
        top:10%;
        right:10%;
        outline: none;
    }
</style>
@stop
@section('content')

<div class="row">
    <div class="col-lg-4 col-md-5">
        <h2>View Details</h2>
    </div>
    <div class="col-lg-4 col-md-5">
        <div class="btn-wrap">
            @if($grievance->status==1 && ($grievance->user_id==$grievance->my_user_id))
                <a href="../view/{{$grievance->id}}" class="btn btn-edit btn-md"><span class="glyphicon glyphicon-pencil"></span>&nbsp; Edit</a>
            @endif
            <a href="{{url('grievance/list')}}" class="btn btn-primary btn-md"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp; Back</a>
            @if(isset($grievance->status) && ($grievance->status==3) )
                @if (isset($grievance->req_reopen))
                    Request to Reopen is already sent.
                @else
                    <button class="btn btn-primary btn-md" data-toggle="modal" data-target="#myModal">Request to ReOpen</button>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-10">
        <div class="cards-view">
            <h3>{{$grievance->title}}</h3>
            <div class="row user">
                <div class="col-md-2">
                    <div class="user-pic">
                    <img src="{{$grievance->userimage}}" alt="User Image">
                    </div>
                </div>
                <div class="col-md-5 user-details border-R">
                    <span id="name">{{$grievance->first_name}} {{$grievance->last_name}}</span><br>
                    <span id="time">{{$grievance->cre_time}}</span>
                </div>
                <div class="col-md-5 user-destails">
                    <span>Category- {{ucwords($grievance->category)}}</span><br>
                    <span>Urgency - {{ucwords(Grievance::getUrgencies($grievance->urgency))}}</span><br>
                    <!--span>
                        <input type="checkbox" name="anonymous" id="anonymous" value="1" {{$grievance->anonymous_val}} disabled>
                        <label for="anonymous">Anonymous</label>
                    </span-->
                </div>
            </div>
            <div class="row content-body">
                
                     @if (isset($grievance->url))
                     <div class="col-md-4 ">
                    <div class="form-group image-preview">
                        <label></label>
                        <img src="{{url($grievance->url)}}" alt="" class="img-thumbnail" />
                    </div>
                    </div>
                    {{ Form::hidden('fid', $grievance->fid) }}
                    @else
                    {{ Form::hidden('fid', '0') }}
                    @endif
                
                <div class="col-md-8">
                    {{$grievance->description}}
                </div>
            </div>
        </div>
        <div ng-app="articleApp" ng-controller="mainCntrl">
            <comment data-section="grievance_view" data-nid="{{$grievance->id}}"></comment>
        </div>
        <div class="">
           <!-- <label for="title">Grievance title</labsel>-->
            <input type="hidden" placeholder="Grievance title" class="form-control" id="title" name="title" value="{{$grievance->title}}" readonly>
        </div>

       <!-- <div class="form-group">
            <label for="body">Body</label>
            <div>{{$grievance->description}}</div>
        </div>-->
    </div>
    <div class="col-md-4"></div>
   <!-- <div class="col-md-4">
        <div class="form-group">
            <label for="category">Category</label>
            <select name="" id="" class="form-control" disabled>
                <option value="">{{ucwords($grievance->category)}}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="urgency">Urgency</label>
            <select name="" id="" class="form-control" disabled>
                <option value="">{{ucwords(Grievance::getUrgencies($grievance->urgency))}}</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        @if (isset($grievance->url))
        <div class="form-group image-preview">
            <label>Image</label>
            <img src="{{url($grievance->url)}}" alt="" class="img-thumbnail" />
        </div>
        {{ Form::hidden('fid', $grievance->fid) }}
        @else
        {{ Form::hidden('fid', '0') }}
        @endif
        <div class="form-group upload-img-wrapper">
            <label for="photo">Photo if any</label>
            <div class="upload-img">
                <input type="file" class="custom-file-input" id="photo" placeholder="Photo" name="photo">
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox" name="anonymous" id="anonymous" value="1" {{$grievance->anonymous_val}} disabled>
            <label for="urgency">Anonymous</label>
        </div> 
    </div>-->
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Reason For Request to reopen</h4>
      </div>
      {{ Form::open(array('url' => 'grievance/request_reopen', 'role' => 'form')) }}
      <div class="modal-body">
        <label for >Reason for Request to Reopen</label>
        <textarea class="form-control"  id="reason" name="reason"></textarea>
        {{ Form::hidden('id', $grievance->id) }}
      </div>
      <div class="modal-footer">
        <input type="submit" name="save" class="btn btn-success btn-md" value="Send Request" />
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
@stop