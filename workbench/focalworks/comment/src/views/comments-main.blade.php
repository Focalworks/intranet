<li class="comment-list-item" ng-show ="!deleted[comment.cid]">
    <div class="comment-group clearfix">
        
        <span ng-if="comment.isaccess">
        <div class="comment-controles-wrapper pull-right">
            <div class="comment-controle-icon comment-controle-icon-normal"><span class="fw-icons glyphicon glyphicon-chevron-down"></span><span class="fw-icons glyphicon glyphicon-chevron-up"></span></div>
            <ul class="comment-controles clearfix">
                <li class="comment-controle first">
                    <div class="comment-controle-inner comment-controle-edit" ng-click="editComment[comment.cid] = false" ng-show="editComment[comment.cid]">Edit <span class="fw-icons glyphicon glyphicon-pencil"></span></div>
                </li>
                <li class="comment-controle last">
                    <div class="comment-controle-inner comment-controle-delete" ng-click="delete_comment(comment)">Delete <span class="fw-icons glyphicon glyphicon-trash"></span></div>
                </li>
            </ul>
        </div>
        </span>

        <div class="comment-display-wrapper clearfix">
            <div class="user-picture">
                <div class="user-picture-inner" ><img ng-src="@{{comment.userimage}}" alt="No Image" /></div>
            </div>
            <div class="comment-display-content">
                <div class="comment-display-name-wrapper clearfix">
                    <div class="pull-left comment-display-name">@{{comment.first_name}} @{{comment.last_name}}</div>
                    <div class="pull-left comment-post-date">@{{comment.created_time}}<span class="fw-icons glyphicon glyphicon-time"></span></div>
                </div>
                <div class="clearfix">
                    <div ng-show="editComment[comment.cid]">@{{comment.comment}}</div>
                    <div ng-hide="editComment[comment.cid]">
                        <textarea class="form-control textare-comment" rows="6" ng-model="comment.comment" ></textarea>
                        <div class="btn-action"><button class="btn btn-success btn-xs" ng-click="post_comment(comment, 'Edit')">Save</button>
                        </div>
                    </div>
                    <div ng-hide="hideReply[comment.cid]" class="margin-T">
                        <textarea class="form-control textare-comment comment-reply" rows="1" ng-model="comment_message"></textarea>
                        <button class="btn btn-success btn-md float-left" ng-click="post_comment(comment)">Post</button>
                    </div>
                </div>
            </div>
            <div class="comment-controle-reply btn btn-xs" ng-click="hideReply[comment.cid] = false" ng-show="hideReply[comment.cid]">Reply <span class="fw-icons glyphicon glyphicon-share-alt"></span></div>
        </div>

    </div>
</li>