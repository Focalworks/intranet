@section('scripts')
@parent<script src="//cdn.ckeditor.com/4.4.3/basic/ckeditor.js"></script>
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
            <input type="text" placeholder="Grievance title" class="form-control" id="title" placeholder="Grievance title" name="title" value="{{$grievance->title}}">
            <span class="error-display">{{$errors->first('title')}}</span>
        </div>
        
        <div class="form-group">
            <label for="body">Body</label>
            <textarea name="body" id="body" class="form-control">{{$grievance->description}}</textarea>
            <span class="error-display">{{$errors->first('body')}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="category">Category</label>
            {{GlobalHelper::getDropdownFromArray('category', Grievance::getGrievanceCategories(), $grievance->category)}}
            <span class="error-display">{{$errors->first('category')}}</span>
        </div>

        
        <div class="form-group">
            <label for="urgency">Urgency</label>
            {{GlobalHelper::getDropdownFromArray('urgency', Grievance::getUrgencies(), $grievance->urgency)}}
            <span class="error-display">{{$errors->first('urgency')}}</span>
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
            <input type="checkbox" name="anonymous" id="anonymous" value="1" {{$grievance->anonymous_val}} {{$grievance->disable_val}}>
            <label for="urgency">Anonymous</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <input type="submit" name="save" class="btn btn-success btn-md" value="Save" />&nbsp;&nbsp;
        <a href="{{url('grievance/list')}}" class="btn btn-primary btn-md">Back</a>
    </div>
</div>
{{ Form::hidden('id', $grievance->id) }}
{{ Form::close() }}

<div ng-app="articleApp" ng-controller="mainCntrl">
<comment data-section="grievance_view" data-nid  ="{{$grievance->id}}"></comment>
</div>

<script>
    $(window).load(function ()
    {
        CKEDITOR.replace('body');
    });
</script>
@stop