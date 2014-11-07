@section('scripts')
@parent<script type="text/javascript" src="{{ asset('js/dev/assessment/assessmentApp.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dev/assessment/assessmentCtrl.js') }}"></script>
@show

@section('content')
<div class="assessment-app-wrapper" ng-app="assessment">
    <h1>Welcome</h1>
    <div ng-view></div>
    <p>After the view</p>
</div>
@show