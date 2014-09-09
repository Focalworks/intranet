@section('scripts')
@parent<script src="//cdn.ckeditor.com/4.4.3/basic/ckeditor.js"></script>
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
            <input type="text" class="form-control" id="title" placeholder="Grievance title" name="title" value="{{$grievance->title}}">
        </div>
        
        <div class="form-group">
            <label for="body">Body</label>
            <textarea name="body" id="body" class="form-control">{{$grievance->description}}</textarea>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="category">Category</label>
            {{GlobalHelper::getDropdownFromArray('category', Grievance::getGrievanceCategories(), $grievance->category)}}
        </div>
        
        <div class="form-group">
            <label for="urgency">Urgency</label>
            {{GlobalHelper::getDropdownFromArray('urgency', Grievance::getUrgencies(), $grievance->urgency)}}
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
</div>
<div class="row">
    <div class="col-md-12">
        <input type="submit" name="save" class="btn btn-success btn-lg" value="Save" />
        <a href="{{url('grievance/list')}}" class="btn btn-primary btn-lg">Back</a>
    </div>
</div>
{{ Form::hidden('id', $grievance->id) }}
{{ Form::close() }}

<script>
    $(window).load(function ()
    {
        CKEDITOR.replace('body');
    });
</script>
@stop