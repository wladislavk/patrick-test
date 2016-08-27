app.controller('messagesController', function($scope, $http, Flash, messageGetter) {
    function getMessagesFromAjax() {
        messageGetter.getMessages()
            .then(function(messages) {
                for (var i=0; i < messages.length; i++) {
                    messages[i].createdAt = messages[i].createdAt.date.replace(/\.0+/, '');
                }
                $scope.messages = messages;
            });
    }
    getMessagesFromAjax();
    $scope.formData = {};
    $scope.submit = function() {
        $http.post('/api/send', $scope.formData)
            .then(function(response) {
                Flash.create('success', 'Message added');
                getMessagesFromAjax();
                $scope.formData.message = '';
            }, function errorCallback(response) {
                Flash.create('danger', response.data.error);
            });
    }
});
