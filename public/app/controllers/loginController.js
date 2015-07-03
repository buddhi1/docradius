angular.module('docradius').controller('loginController', [ '$scope', '$http' , '$rootScope', '$location', 'USER_TYPE', function($scope, $http, $rootScope, $location, USER_TYPE){
	$scope.login = {};
	$scope.login.type = USER_TYPE;

	//scope initialization
	$scope.init = function(){
		//check login state
		$http.get('/drad/auth/state')
		.success(function(res){
			$rootScope.currentUser = res.data.user;
			$location.path('/');
		})
		.error(function(res){

		})
	}

	$scope.init();

	//logs in user to the api
	$scope.requestLogin = function(login){
		$http.post('/drad/auth/login', login)
		.success(function(res){
			$rootScope.currentUser = res.data.user;
		})
		.error(function(res){
			alert(JSON.stringify(res));
		});
	};
}]);