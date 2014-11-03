@section('scripts')
@parent<script src="//cdn.ckeditor.com/4.4.3/basic/ckeditor.js"></script>
<script type="text/javascript" src="{{ asset('js/dev/comment/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dev/comment/add_comments.js') }}"></script>
@stop
@section('content')
{{ Form::open(array('url' => 'grievance/update', 'role' => 'form', 'files' => true)) }}

<div class="row">
    <div class="col-lg-4 col-md-5">
        <h2>Edit</h2>
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
                    <input type="text" placeholder="Grievance title" class="form-control" id="title" placeholder="Grievance title" name="title" value="{{$grievance->title}}">
                    <span class="error-display">{{$errors->first('title')}}</span>
                </div>
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
                 <div class="form-group">
                    <label for="body">Body</label>
                    <textarea name="body" id="body" class="form-control">{{$grievance->description}}</textarea>
                    <span class="error-display">{{$errors->first('body')}}</span>
                </div>
            </div>
            <div class="col-md-4">
                 <div class="form-group">
                    <label for="urgency">Anonymous</label>&nbsp;
                    <input type="checkbox" name="anonymous" id="anonymous" value="1" {{$grievance->anonymous_val}} {{$grievance->disable_val}}>
                </div>
                <div class="form-group">
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
                        <!--<label for="photo">Photo if any</label>-->
						@if (isset($grievance->url))
						 <div class="reload-img">
						 @else
						 <div class="upload-img">
						 @endif
                            <input type="file" class="custom-file-input" id="photo" placeholder="Photo" name="photo">
                        </div>
                    </div>
                </div>
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
                CKEDITOR.replace('body',{
                    height:'120px',
                });
            });
        </script>
    </div>
</div>

@stop

