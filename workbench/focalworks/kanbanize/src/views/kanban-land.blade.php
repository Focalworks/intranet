@section('content')
<div ng-app="kanbanize">
    <div ng-view></div>
</div>

<script type="text/javascript" src="{{ asset('js/kanbanize/kanbanize_app.js') }}"></script>
@stop