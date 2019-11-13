/**
 * Copyright (C) 2017, MEGAADS - All Rights Reserved
 *
 * This software is released under the terms of the proprietary license.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */
var system = angular.module("system",
    [
        "ngSanitize",
        "ngMaterial",
        "ngAnimate",
        "ngFileUpload",
        "dynamicNumber",
        "localytics.directives",
        "ui.sortable"
    ]);

system.config(['chosenProvider', function (chosenProvider) {
    chosenProvider.setOption({
        no_results_text: 'Không tìm thấy kết quả!',
        placeholder_text_multiple: 'Chọn một từ khóa',
        placeholder_text: 'Chọn một từ khóa'
    });
}]);

system.directive('icheck', function($timeout, $parse) {
    return {
      require: 'ngModel',
      link: function($scope, element, $attrs, ngModel) {
          return $timeout(function() {
              var value;
              value = $attrs['value'];
              $scope.$watch($attrs['ngModel'], function(newValue){
                  $(element).iCheck('update');
              })
              return $(element).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
              }).on('ifChanged', function(event) {
                  if ($(element).attr('type') === 'checkbox' && $attrs['ngModel']) {
                      $scope.$apply(function() {
                          return ngModel.$setViewValue(event.target.checked);
                      });
                  }
                  if ($(element).attr('type') === 'radio' && $attrs['ngModel']) {
                      return $scope.$apply(function() {
                          return ngModel.$setViewValue(value);
                      });
                  }
              });
          });
      }
  };
});

system.directive('ckEditor', function () {
    return {
        require: '?ngModel',
        link: function (scope, elm, attr, ngModel) {
            var ck = CKEDITOR.replace(elm[0], {
                language: 'vi',
                removePlugins : 'resize',
                filebrowserBrowseUrl: 'https://s3.shopbay.vn/laravel-filemanager',
                // filebrowserBrowseUrl: 'https://s3.shopbay.vn/laravel-filemanager?type=Files',
                filebrowserUploadUrl: 'https://s3.shopbay.vn/upload?',
                fileTools_requestHeaders : {
                    'token': $('meta[name="shopbay-token"]').attr('content')
                },
                height: 500,
                allowedContent: true,
            });
            ck.on( 'fileUploadResponse', function( evt ) {
                evt.stop();
                var data = evt.data,
                    xhr = data.fileLoader.xhr,
                    response = xhr.responseText;
                    if (response) {
                        response = JSON.parse(response);
                        if (response.upload && response.upload[0]) {
                            data.url = response.upload[0];
                        }
                    }
            } );
            if (!ngModel) return;
            ck.on('change', function () {
                scope.$apply(function () {
                    ngModel.$setViewValue(ck.getData());
                });
            });
            ck.on('instanceReady', function() {
                ck.setData(ngModel.$viewValue);
             });
            ck.on('blur', function () {
                scope.$apply(function () {
                    ngModel.$setViewValue(ck.getData());
                });
            });
            ngModel.$render = function (value) {
                ck.setData(ngModel.$viewValue);
            };
        }
    };
});

system.directive('myDatePicker', function () {
        return {
            restrict: 'A',
            require: '?ngModel',
            link: function (scope, element, attrs, ngModelController) {

                // Private variables
                var datepickerFormat = 'd/m/yyyy',
                    momentFormat = 'D/M/YYYY',
                    datepicker,
                    elPicker;

                // Init date picker and get objects http://bootstrap-datepicker.readthedocs.org/en/release/index.html
                datepicker = element.datepicker({
                    autoclose: true,
                    keyboardNavigation: false,
                    todayHighlight: true,
                    format: datepickerFormat
                });
                elPicker = datepicker.data('datepicker').picker;

                // Adjust offset on show
                datepicker.on('show', function (evt) {
                    elPicker.css('left', parseInt(elPicker.css('left')) + +attrs.offsetX);
                    elPicker.css('top', parseInt(elPicker.css('top')) + +attrs.offsetY);
                });

                // Only watch and format if ng-model is present https://docs.angularjs.org/api/ng/type/ngModel.NgModelController
                if (ngModelController) {
                    // So we can maintain time
                    var lastModelValueMoment;

                    ngModelController.$formatters.push(function (modelValue) {
                        //
                        // Date -> String
                        //

                        // Get view value (String) from model value (Date)
                        var viewValue,
                            m = moment(modelValue, momentFormat, true);
                        if (modelValue && m.isValid()) {
                            // Valid date obj in model
                            lastModelValueMoment = m.clone(); // Save date (so we can restore time later)
                            viewValue = m.format(momentFormat);
                        } else {
                            // Invalid date obj in model
                            lastModelValueMoment = undefined;
                            viewValue = undefined;
                        }

                        // Update picker
                        element.datepicker('update', viewValue);

                        // Update view
                        return viewValue;
                    });

                    ngModelController.$parsers.push(function (viewValue) {
                        //
                        // String -> Date
                        //

                        // Get model value (Date) from view value (String)
                        var modelValue,
                            m = moment(viewValue, momentFormat, true);
                        if (viewValue && m.isValid()) {
                            // Valid date string in view
                            if (lastModelValueMoment) { // Restore time
                                m.hour(lastModelValueMoment.hour());
                                m.minute(lastModelValueMoment.minute());
                                m.second(lastModelValueMoment.second());
                                m.millisecond(lastModelValueMoment.millisecond());
                            }
                            // modelValue = m.toDate();
                            modelValue = m.format(momentFormat);
                        } else {
                            // Invalid date string in view
                            modelValue = undefined;
                        }

                        // Update model
                        return modelValue;
                    });

                    datepicker.on('changeDate', function (evt) {
                        // Only update if it's NOT an <input> (if it's an <input> the datepicker plugin trys to cast the val to a Date)
                        if (evt.target.tagName !== 'INPUT') {
                            ngModelController.$setViewValue(moment(evt.date).format(momentFormat)); // $seViewValue basically calls the $parser above so we need to pass a string date value in
                            ngModelController.$render();
                        }
                    });
                }

            }
        };
    });

system.config(['dynamicNumberStrategyProvider', function (dynamicNumberStrategyProvider) {
    dynamicNumberStrategyProvider.addStrategy('price', {
        numInt: 10,
        numFract: 4,
        numSep: ',',
        numThousand: true
    });
}]);

system.factory('httpRequestInterceptor', function () {
    return {
      request: function (config) {

        config.headers['token'] = $('meta[name="shopbay-token"]').attr('content');
        config.headers['shopUuid'] = $('meta[name="shop-uuid"]').attr('content');
        return config;
      }
    };
  });

  system.config(function ($httpProvider) {
    $httpProvider.interceptors.push('httpRequestInterceptor');
  });
