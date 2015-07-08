// for bootstrap input group
var commonDirectives = angular.module('commonDirectives', []);

commonDirectives.directive('inputgroup', function(){
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
});

// active uneditable 'readonly' inputfields
commonDirectives.directive('toggleInput', function(){
	return {
		restrict: "A",
		scope: {
			editState: '@editState',
			onEditStart: '@editStart',
			onEditEnd: '@editEnd',
		},
		compile: function(element, attrs, transcludeFn){
			return function (scope, element, attrs, ctrl) {

				// watch for edit state change
				scope.$watch('editState', function(){
					if(scope.editState != 'true'){
						element.attr('readonly', 'true');
						element.addClass('readonly-active');
					}else{
						element.removeAttr('readonly');
						element.removeClass('readonly-active');
					}
				});
			}
		}
	}
});
