@section('scripts')
@parent<script src="//cdn.ckeditor.com/4.4.3/basic/ckeditor.js"></script>
<script type="text/javascript" src="{{ asset('packages/focalworks/comment/js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/focalworks/comment/js/custom/add_comments.js') }}"></script>
@stop
@section('content')
<div class="row">
    <div class="col-md-12"><h1>Details</h1></div>
</div>

<div class="row">
    <div class="col-md-4">

        <div class="form-group">
            <label for="title">Grievance title</label>
            <input type="text" placeholder="Grievance title" class="form-control" id="title" name="title" value="{{$grievance->title}}" readonly>
        </div>

        <div class="form-group">
            <label for="body">Body</label>
            <div>{{$grievance->description}}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="category">Category</label>
            <select name="" id="" class="form-control" disabled>
                <option value="">{{ucwords($grievance->category)}}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="urgency">Urgency</label>
            <select name="" id="" class="form-control" disabled>
                <option value="">{{ucwords($grievance->urgency)}}</option>
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
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <a href="{{url('grievance/list')}}" class="btn btn-primary btn-md">Back</a>
    </div>
</div>

<div ng-app="articleApp" ng-controller="mainCntrl">
<comment data-section="grievance_view" data-nid="{{$grievance->id}}"></comment>
</div>
@stop