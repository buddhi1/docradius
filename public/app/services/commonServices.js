angular.module('commonServices', [])
.factory('authService', [ '$http','$rootScope','$location',function($http, $rootScope, $location){
	return {
		chackAuthState: function(){
			if(!$rootScope.currentUser){
				//rerutns a promise object
				$http.get('/drad/auth/state')
				.success(function(res) {
					$rootScope.currentUser = res.data.user;
					return Promise.resolve();
				})
				.error(function(){
					$location.path('/login');
				});
			}
		},
	}
}]);