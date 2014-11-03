@section('scripts')
@parent
<script type="text/javascript" src="{{ asset('js/dev/comment/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dev/comment/add_comments.js') }}"></script>
@stop
@section('content')
{{ Form::open(array('url' => 'grievance/update', 'role' => 'form', 'files' => true)) }}
<div class="row">
    <div class="col-lg-4 col-md-5">
        <h2>Manage Details</h2>
    </div>
    <div class="col-lg-4 col-md-5">
        <div class="btn-wrap">
            <button type="submit" class="btn btn-success btn-md"><span class="glyphicon glyphicon-save"></span>&nbsp;Save</button>
            <a href="{{url('grievance/list')}}" class="btn btn-primary btn-md"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-10">
        <div class="row cards-view">    
            <div class="col-md-8">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="" name="" value="{{$grievance->title}}" readonly>
                     {{ Form::hidden('title', $grievance->title) }}
                </div>
                 <div class="form-group">
                    <label for="category">Category</label>
                     <select name="category" class="form-control">
                        <option value="{{$grievance->category}}">{{ucwords($grievance->category)}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="urgency">Urgency</label>
                    <select name="urgency" class="form-control">
                        <option value="{{$grievance->urgency}}">{{Grievance::getUrgencies($grievance->urgency)}}</option>
                    </select>
                </div>
                 <div class="form-group">
                    <label for="urgency">Status</label>
                    {{GlobalHelper::getDropdownFromArray('status', Grievance::getStatusName(), $grievance->status)}}
                </div>
                <div class="form-group">
                    <label for="body">Body</label>
                     <div>{{$grievance->description}}</div>
                </div>
                 {{ Form::hidden('body', $grievance->description) }}
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    @if (isset($grievance->url))
                    <p>Image</p>
                    <img src="{{url($grievance->url)}}" alt="" class="img-thumbnail" />
                    {{ Form::hidden('fid', $grievance->fid) }}
                    @else
                    {{ Form::hidden('fid', '0') }}
                    @endif
                </div>
                <div class="form-group">
                    <label for="urgency">Anonymous</label>&nbsp;
                    <input type="checkbox" name="anonymous" id="anonymous" value="1" {{$grievance->anonymous_val}} {{$grievance->disable_val}}>
                </div>
            </div>
        </div>
   
{{ Form::hidden('id', $grievance->id) }}
{{ Form::close() }}

    <div ng-app="articleApp" ng-controller="mainCntrl">
        <comment data-section="grievance_view" data-nid  ="{{$grievance->id}}"></comment>
    </div>
 </div>
</div>

@stop