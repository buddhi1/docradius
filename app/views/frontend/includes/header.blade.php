<html ng-app="docradius">
<head>
	<title>{{ $title or 'docradius' }}</title>
	<link rel="stylesheet" type="text/css" href="">
	<script type="text/javascript" src="/vendor/angular/angular.min.js"></script>
	<script type="text/javascript" src="/vendor/angular-route/angular-route.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/vendor/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/vendor/bootstrap/dist/css/bootstrap-theme.min.css">
	<script type="text/javascript">
		angular.module('docradius',["ngRoute"])
		.config(['$httpProvider', '$routeProvider' , 'CSRF_TOKEN', 'USER_TYPE',function($httpProvider, $routeProvider, CSRF_TOKEN, USER_TYPE) {
			
			//setting up Ajax http request parameters
			$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
			$httpProvider.defaults.transformRequest = function(data){
		        if (data === undefined || typeof(data) != 'object') {
		            return data;
		        }
		        var serialized = '';
		        for(var key in data){
		        	serialized += key + '=' + data[key] + '&';
		        }
		        serialized += '_token=' + CSRF_TOKEN;
		        return serialized;
		    }

		    //setting up the routes
		    $routeProvider.when("/login", {
		    	templateUrl: "/app/components/login.html",
		    	controller: "loginController",
		    	data: {
		    		auth: false,
		    	}
		    });

		    //yeilds routes according to user type
		    @yield('routes')

		}])
		.run([ '$rootScope', '$location', function( $rootScope, $location ){
			$rootScope.$on( "$routeChangeStart", function(event, next, current) {
				if( next.data.auth && !$rootScope.currentUser ){
					$location.path('/login');
				}else{

				}
			});
		}])
		.constant("CSRF_TOKEN", '{{ csrf_token() }}')
		.constant("USER_TYPE", '{{ $USER_TYPE }}');
	</script>
	@yield('controllers')
	@yield('directives')
</head>
<body>
	@yield('content')
</body>
</html>