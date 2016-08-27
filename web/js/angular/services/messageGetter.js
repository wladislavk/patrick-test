app.factory('messageGetter', function($http) {
    return {
        getMessages: function() {
            return $http.get('/api/get')
                .then(function(response) {
                    return response.data.messages;
                });
        }
    };
});
