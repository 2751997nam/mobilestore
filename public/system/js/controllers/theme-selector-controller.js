system.controller("ThemeSelectorController", ThemeSelectorController);

function ThemeSelectorController($scope, $http, $rootScope, $timeout) {
    $scope.baseController = this.__proto__ = new BaseController($scope, $http, $rootScope);

    $scope.controllerName = "ThemeSelectorController";
    $scope.themes = [];
    $scope.themeSelected = [];
    $scope.themeDetail = {};
    $scope.themeOptionId = 0;

    $scope.initialize = function () {
        $scope.fetchTheme()
    };

    $scope.fetchTheme = function () {
        $http.get(apiUrl + '/theme')
            .then(function (response) {
                $scope.getSelectedTheme(response.data.result);
            });
    };

    $scope.getSelectedTheme = function (themes) {
        $http.get(apiUrl + '/option?filters=key=theme')
            .then(function (response) {
                let selectedTheme = {value: 'shopbay'};
                if (typeof response.data.result[0] != 'undefined') {
                    selectedTheme = response.data.result[0];
                    $scope.themeOptionId = selectedTheme.id;
                }

                for (let i = 0; i < themes.length; i++) {
                    if (themes[i].slug === selectedTheme.value) {
                        themes[i].selected = true;
                        let themeSelected = themes[i];

                        $scope.themeDetail = themes[i];
                        themes.splice(i, 1);
                        themes.unshift(themeSelected);
                        break;
                    }
                }
                $scope.themes = themes;

            });
    };

    $scope.openDetail = function (theme) {
        $scope.themeDetail = theme;
        $scope.baseController.openDialogModal('#modalThemeDetail');
    };

    $scope.activate = function (theme) {
        if ($scope.themeOptionId) {
            $http({
                method: "PUT",
                url: apiUrl + "/option/" + $scope.themeOptionId,
                data: {value: theme.slug}

            }).then(function (response) {
                $scope.fetchTheme();
                toastr.success("Đã kích hoạt giao diện: " + theme.name);
                $("html, body").animate({scrollTop: 0}, "slow");

                installModuleDependencies(theme);

            })

        } else {
            $http({
                method: "POST",
                url: apiUrl + "/option",
                data: {
                    name: 'Theme Selector',
                    value: theme.slug,
                    key: 'theme'
                }

            }).then(function (response) {
                $scope.fetchTheme();
                installModuleDependencies(theme);
            })
        }

        // Kích hoạt thành công -> cài các module phụ thuộc


    };

    function installModuleDependencies(theme) {
        if (typeof theme.modules != 'undefined' && theme.modules != null) {
            let namespaces = JSON.parse(theme.modules);
            // console.log(namespaces);
            if(namespaces.length){
                toastr.warning("Đang cài đặt ứng dụng phụ thuộc . . .");
                $http({
                    method: "POST",
                    url: apiUrl + "/app/install-bulk",
                    data: {
                        namespaces: namespaces
                    }

                }).then(function () {
                    $scope.fetchTheme();
                    toastr.success("Cài ứng dụng phụ thuộc thành công: ");
                })
            }
        }
    }

    $scope.initialize();
}
