@section('content')
<div ng-app="kanbanize">
    <div ng-view></div>
</div>

<script type="text/javascript" src="{{ asset('js/prod/kanbanize.min.js') }}"></script>
@stop