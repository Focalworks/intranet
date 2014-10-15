var Comments = angular.module('Comments', []);

Comments.factory('commentsService', ['$http', '$rootScope', function($http, $rootScope) {
    var comments = [];
 
    return {
        fetchComments: function(nid, section) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'comment/get',
                method: "POST",
                data: $.param({'nid' : nid, 'section' : section})
            })
                .success(function(json_comment_data) {
                    if(json_comment_data) {
                        comments = json_comment_data.comments;
                    }
                    else {
                        comments = {};
                    }
                    // quiz = addData;
                    $rootScope.$broadcast('handleProjectsBroadcast', comments);
                });
        },
        saveComments: function(section, comment, nid, parent_cid,op) {
            var commentData = {};
            commentData.section = section;
            commentData.comment = comment;
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'comment/save',
                method: "POST",
                data: $.param({'data' : JSON.stringify(commentData), 'nid': nid, 'parent_cid': parent_cid, 'op' : op})
            })
                .success(function(json_comment_data) {
                    comments = json_comment_data;
                    $rootScope.$broadcast('handleProjectsBroadcast', comments);
                });
        },
        removeComments: function(commentData) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'comment/delete',
                method: "POST",
                data: $.param({'data' : JSON.stringify(commentData)})
            })
                .success(function() {
                });
        }
    };
}]);

/** Directive to render all nested comments **/
Comments.directive('comment', function(commentsService) {
    return {
        restrict: 'E',
        scope:  {
            section: '@',
            nid: '='
        },
        replace: true,
        templateUrl: base_url + 'comment/comment-wrapper-template',
        link: function($scope, element, attrs, myCtrl) {
        },
        controller: function($scope, commentsService) {
            $scope.showPopup = false;
            $scope.commentPosted = false;
            var nid = $scope.nid;
            var section = $scope.section;
            /** Fetch all comments **/
            commentsService.fetchComments(nid, section).then(function(commentData) {
                if(commentData.data) {
                    $scope.all_comments = commentData.data;
                }
                else {
                    $scope.all_comments = {};
                }

            });

            $scope.showPopup = true;
            $scope.commentPosted = false;

            /** Post new comment **/
            $scope.new_post_comment = function(commentMessage) {
                commentsService.saveComments($scope.section, commentMessage, $scope.nid, 0).then(function(commentObj) {
                    $scope.all_comments.push(commentObj.data);
                    $scope.new_comment_message = '';
                });
            }
        }
    }
});

/** Directive to render parent child tree **/
Comments.directive('commentTree', function () {
    return {
        restrict: "E",
        replace: true,
        scope: {
            collection: '=',
            nid: '=',
            section : '='
        },
        template: '<ul class="comments-wrapper"><comment_item ng-repeat="comment in collection" comment="comment" nid="nid" section="section"></comment_item></ul>'
    }
})

/** Directive to render single comment **/
Comments.directive('commentItem', ['$compile', 'commentsService', function($compile, commentsService){
    return {
        restrict: "E",
        replace: true,
        scope: {
            comment: '=',
            nid: '=',
            section : '='
        },
        templateUrl: base_url + 'comment/comment-template',
        link: function ($scope, element, attrs) {
            /* For comment children */
            if (angular.isArray($scope.comment.children)) {
                var newMemEL = angular.element("<comment_tree collection='comment.children' nid='nid' section='section'></comment_tree>");
                element.append(newMemEL);
                $compile(newMemEL)($scope);
            }

            $scope.hideReply = {};
            $scope.editComment ={};
            $scope.deleted = {}
            $scope.deleted[$scope.comment.cid] = false;
            $scope.hideReply[$scope.comment.cid] = true;
            $scope.editComment[$scope.comment.cid] = true;

            /** Submit function for reply comment posted and save of edit comment  **/
            $scope.post_comment = function(commentData, op) {
                /* For edit update the comment */
                if(op == 'Edit') {
                    commentsService.saveComments($scope.section, commentData.comment, $scope.nid, commentData.cid, op).then(function(commentObj) {
                        $scope.editComment[$scope.comment.cid] = true;
                    });
                }
                else {
                    if($scope.comment_message) {
                        commentsService.saveComments($scope.section, $scope.comment_message, $scope.nid, commentData.cid).then(function(commentObj) {

                            /* For first time if children is created */
                            if($scope.comment.children == undefined) {
                                $scope.comment.children = [];
                                $scope.comment.children.push(commentObj.data);
                                var newMemEL = angular.element("<comment_tree collection='comment.children' nid='nid' section='section'></comment_tree>"); /* Recursive directive being called */
                                element.append(newMemEL);
                                $compile(newMemEL)($scope);
                            }
                            else {
                                $scope.comment.children.push(commentObj.data);
                            }

                            $scope.comment_message = '';
                            $scope.hideReply[$scope.comment.cid] = true;
                        });
                    }
                }
            }

            /** Submit function to delete comment  **/
            $scope.delete_comment = function(CommentData) {
                commentsService.removeComments(CommentData).then(function(commentObj) {
                    $scope.comment = {};
                    $scope.deleted = {}
                    $scope.deleted[$scope.comment.cid] = true;
                    $scope.hideReply[$scope.comment.cid] = true;
                    $scope.editComment[$scope.comment.cid] = true;
                });
            }
        }
    }
}]);
