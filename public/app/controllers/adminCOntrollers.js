angular.module('docradius').controller('adminController',[ '$scope', '$http', function($scope,$http){
	$scope.data = {};

	$http.get('/drad/admin/user')
	.success(function(res){
		$scope.data.admins = res.data.users;
	});

	$scope.addAdmin = function(newAdmin){
		if($scope.adminAdd.$valid){
			$http.post('/drad/admin/user', newAdmin);
		}
	}
}]);