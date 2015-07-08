angular.module('docradius').controller('adminController',[ '$scope', '$http', '$state',  function($scope,$http,$state){
	$scope.data = {};

	//explicitly defining form objects
	$scope.forms = {};


	$scope.getAdminList = function(){
		$http.get('/drad/admin/user')
		.success(function(res){
			$scope.data.admins = res.data.users;
		})
		.error(function(res){
			console.log(res);
			alert('error');
		});
	}

	$scope.addAdmin = function(newAdmin){
		if($scope.forms.adminAdd.$valid){
			$http.post('/drad/admin/user', newAdmin)
			.success(function(res){
				//$location.path('/administrators');
				$state.go('panel.administrators.listAdmins');
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
		}
	}

	$scope.editAdmin = {id: '', email: '', password: '_filler'};
	//watch for changes in the edit admin id and load admin details
	$scope.$watch('editAdmin.id', function(newValue){
		if(!newValue || newValue === undefined || newValue == null) return;
		$http.get('drad/admin/user/'+newValue)
		.success(function(res){
			$scope.editAdmin.email = res.data.admin.email;
			$scope.editAdmin.password = '_filler';
		})
		.error(function(res){
			console.log(error);
			alert('error');
		});
	});

	$scope.updateAdmin = function(editAdmin){
		if($scope.forms.adminEdit.$invalid) return;

		var updateVars = editAdmin;
		if( editAdmin.password == '_filler' ) delete updateVars.password;
		console.log(updateVars);
		$http.put('drad/admin/user/'+editAdmin.id, updateVars)
			.success( function(res){
				console.log(res);
				$state.go('panel.administrators.listAdmins');
			})
			.error( function(res){
				console.log(res);
				alert('error');
			});
	}

	$scope.deleteAdmin = function(userId){
		$http.delete('/drad/admin/user/' + userId)
			.success( function(res){
				console.log(res);
				$scope.getAdminList();
			})
			.error( function(res){
				console.log(res);
				alert('error');
			});
	}


	// $scope.$on("$routeChangeSuccess", function (event, next, current) {
	// 	if(next.data.view == 'edit'){
	// 		$scope.editAdmin.id = $routeParams.id;
	// 		$http.get('drad/admin/user/'+$scope.editAdmin.id)
	// 		.success(function(res){
	// 			$editAdmin.email = res.data.admin.email;
	// 			$editAdmin.password = '*sample*';
	// 		});
	// 	}
	// });
}]);