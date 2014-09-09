@section('scripts')
@parent<script src="//cdn.ckeditor.com/4.4.3/basic/ckeditor.js"></script>
@stop
@section('content')
<?php
$urgency = Input::old('urgency');
if ($urgency == '') {
    $urgency = 1;
}
?>
<div class="row">
    <div class="col-md-12"><h1>Add Grievance</h1></div>
</div>

{{ Form::open(array('url' => 'grievance/save', 'role' => 'form', 'files' => true)) }}
<div class="row">
    <div class="col-md-6">
        
        <div class="form-group">
            <label for="title">Grievance title</label>
            <input type="text" class="form-control" id="title" placeholder="Grievance title" name="title" value="{{Input::old('title')}}">
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
    
        <div class="form-group upload-img-wrapper">
            <label for="photo">Photo if any</label>
            <div class="upload-img">
                <input type="file" class="form-input custom-file-input" placeholder="Photo" name="photo">
            </div>
        </div>    
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <input type="submit" name="save" class="btn btn-success btn-md" value="Save" />&nbsp;&nbsp;
        <a href="{{url('grievance/list')}}" class="btn btn-primary btn-md">Back</a>
    </div>
</div>
{{ Form::close() }}
<script>
    $(window).load(function ()
    {
        CKEDITOR.replace('body');
    });
</script>
@stop