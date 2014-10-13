@section('scripts')
@parent
<script type="text/javascript" src="{{ asset('js/dev/comment/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dev/comment/add_comments.js') }}"></script>
@stop
@section('content')
<div class="row">
    <div class="col-md-12"><h1>Details</h1></div>
</div>

{{ Form::open(array('url' => 'grievance/update', 'role' => 'form', 'files' => true)) }}
<div class="row">
    <div class="col-md-4">
        
        <div class="form-group">
            <label for="title">Grievance title</label>
            <input type="text" class="form-control" id="" name="" value="{{$grievance->title}}" readonly>
             {{ Form::hidden('title', $grievance->title) }}
        </div>
        
        <div class="form-group">
            <label for="body">Body</label>
             <div>{{$grievance->description}}</div>
        </div>
         {{ Form::hidden('body', $grievance->description) }}
    </div>

    <div class="col-md-4">
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
    </div>

    <div class="col-md-4">
        @if (isset($grievance->url))
        <p>Image</p>
        <img src="{{url($grievance->url)}}" alt="" class="img-thumbnail" />
        {{ Form::hidden('fid', $grievance->fid) }}
        @else
        {{ Form::hidden('fid', '0') }}
        @endif
    </div>
    <div class="form-group">
        <input type="checkbox" name="anonymous" id="anonymous" value="1" {{$grievance->anonymous_val}} {{$grievance->disable_val}}>
        <label for="urgency">Anonymous</label>
        </div>
</div>
<div class="row">
    <div class="col-md-12">
        <input type="submit" name="save" class="btn btn-success btn-lg" value="Save" />
        <a href="{{url('grievance/list')}}" class="btn btn-primary btn-lg">Back</a>
    </div>
</div>
{{ Form::hidden('id', $grievance->id) }}
{{ Form::close() }}

<div ng-app="articleApp" ng-controller="mainCntrl">
<comment data-section="grievance_view" data-nid  ="{{$grievance->id}}"></comment>
</div>

@stop