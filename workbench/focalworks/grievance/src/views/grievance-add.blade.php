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

{{ Form::open(array('url' => 'grievance/save', 'role' => 'form', 'files' => true)) }}
<div class="row">
    <div class="col-lg-4 col-md-5">
        <h2>Add Grievance</h2>
    </div>
    <div class="col-lg-4 col-md-5">
        <div class="btn-wrap">
            <button type="submit" class="btn btn-success btn-md"><span class="glyphicon glyphicon-floppy-save"></span>&nbsp; Save</button>
            <a href="{{url('grievance/list')}}" class="btn btn-primary btn-md"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp; Back</a>
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
@stop