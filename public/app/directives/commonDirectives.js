angular.module('commonDirectives', [])
.directive('inputgroup', function(){
	//inserts a bootstrap input group with a matching html input
	return {
		restrict: "E",
	    replace: true,
	    require: 'ngModel',
	    scope: {
	    	inputValue: '=ngModel',
	    	inputLabel: '@label',
	    	inputType: '@type',
	    	inputName: '@name',
	    	inputValidation: '@validation',
	    },	
	    template: '<div class="input-group">\
				<span class="input-group-addon">{{ inputLabel }}</span>\
				<input class="form-control" ng-model="inputValue" />\
			</div>',
		compile: function (element, attrs, transcludeFn) {
			var inputElement = element.find('input')[0];
			inputElement.type = attrs.type || 'text';
			inputElement.name = attrs.name;
			if(typeof(attrs.required) == 'string'){
				inputElement.required = "true"
			}
			return function (scope, element, attrs, ctrl) {};
		}
	}
})