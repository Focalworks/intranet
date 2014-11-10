@section('content')
<div class="assessment-app-wrapper" ng-app="assessment">
    <div ng-view></div>
</div>
<script type="text/javascript" src="{{ asset('js/prod/assessment.min.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('js/dev/assessment/assessmentApp.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dev/assessment/assessmentCtrl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dev/assessment/assessmentFact.js') }}"></script>--}}
@show