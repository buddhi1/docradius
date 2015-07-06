angular.module('docradius').controller('adminController',[ '$scope', '$http', '$location', '$routeParams', function($scope,$http,$location,$routeParams){
	$scope.data = {};

	$http.get('/drad/admin/user')
	.success(function(res){
		$scope.data.admins = res.data.users;
	});

	$scope.addAdmin = function(newAdmin){
		if($scope.adminAdd.$valid){
			$http.post('/drad/admin/user', newAdmin)
			.success(function(res){
				$location.path('/administrators');
			})
			.error(function(res){
				console.log(res);
				alert('error');
			})
		}
	}

	$scope.editAdmin = {};

	$scope.$on("$routeChangeSuccess", function (event, next, current) {
		if(next.data.view == 'edit'){
			$scope.editAdmin.id = $routeParams.id;
			$http.get('drad/admin/user/'+$scope.editAdmin.id)
			.success(function(res){
				$editAdmin.email = res.data.admin.email;
				$editAdmin.password = '*sample*';
			});
		}
	});
}]);