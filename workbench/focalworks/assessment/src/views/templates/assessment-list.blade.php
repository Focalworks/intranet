<div class="row">
<div class="col-md-4">
    <ul class="list-group">
        <li ng-repeat="question in questions" class="list-group-item">
        <span class="badge">
            <i ng-repeat="tag in question.tags">@{{tag.name }}</i>
        </span>
        {{--@{{question}}--}}
        <p><strong>@{{question.title}}</strong></p>
        <div class="options">
            <ul class="list-group">
                <li ng-repeat="option in question.options" class="list-group-item">
                {{--@{{option }}--}}
                <span ng-if="option.correct == 1" class="float-right">Correct</span>
                @{{option.option }}
                </li>
            </ul>
        </div>
        </li>
    </ul>
</div>
</div>