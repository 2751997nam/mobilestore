system.controller("MenuController", MenuController);
system.directive('compile', ['$compile', function ($compile) {
    return function (scope, element, attrs) {
        scope.$watch(
            function (scope) {
                // watch the 'compile' expression for changes
                return scope.$eval(attrs.compile);
            },
            function (value) {
                // when the 'compile' expression changes
                // assign it into the current DOM
                element.html(value);

                // compile the new DOM and link it to the current
                // scope.
                // NOTE: we only compile .childNodes so that
                // we don't get into infinite loop compiling ourselves
                $compile(element.contents())(scope);
            }
        );
    };
}]);

/**
 * Menu Controller.
 * @param {*} $scope 
 * @param {*} $http 
 * @param {*} $rootScope 
 * @param {*} $timeout 
 * @param {*} $sanitize 
 * @param {*} $sce 
 * @param {*} $compile 
 */
function MenuController($scope, $http, $rootScope, $timeout, $sanitize, $sce, $compile) {

    $scope.allCategories = [];
    $scope.menuSetting = [];
    $scope.optionMenu = {};
    $scope.allSelected = false;
    $scope.selectedCategory = {};
    $scope.menuHtml = '';
    $scope.option = {
        auto: true
    };
    $scope.isSearching = false;
    $scope.searchCategories = [];
    $scope.categoryMode = 'allCate';
    $scope.custom = {
        name: '',
        url: '',
    };
    $scope.isToggleAll = false;


    this.__proto__ = new BaseController($scope, $http, $rootScope);

    /**
     * Initialize 
     */
    $scope.initialize = function () {
        $scope.getCategories();
        $scope.getMenuSetting();
    }

    /**
     * Get all categories for setting menu
     */
    $scope.getCategories = function () {
        var url = $scope.buildUrl('/category?sorts=-id&page_size=-1');
        $http.get(url)
            .then(response => {
                if (response.data.status == 'successful') {
                    $scope.allCategories = rebuildCategory(response.data.result);
                }
            })
    }

    /**
     * Get menu from config on server then render to view
     */
    $scope.getMenuSetting = function () {
        var url = $scope.buildUrl('/option?filters=key=general.menu&metric=first');
        $http.get(url)
            .then(response => {
                if (response.data.status == 'successful' && response.data.result) {
                    var result = response.data.result;
                    $scope.optionMenu = response.data.result;
                    var resultValue = JSON.parse(result.value);
                    if (typeof resultValue.menu !== 'undefined ') {
                        $scope.menuSetting = resultValue.menu;
                    }
                    if (typeof resultValue.option_auto !== 'undefined') {
                        $scope.option.auto = resultValue.option_auto;
                    }
                    setStorageMenu($scope.menuSetting)
                    $scope.menuHtml = $scope.buildHtmlMenu($scope.menuSetting);
                } else {
                    $scope.createConfigNotExist();
                }
            });
    }

    /**
     * Build tree menu html
     */
    $scope.buildHtmlMenu = function (menus) {
        var html = '<ol class="dd-list">';
        menus.forEach(function (menu) {
            html += `<li class="dd-item" data-id="${menu.id}" 
            data-parent_id="${menu.parent_id}" 
            data-name="${menu.name}" 
            data-slug="${menu.slug}"
            data-type="${menu.type}"
            data-url="${menu.url}"
            data-is_hidden="${menu.is_hidden}"
            data-dsp_type="${menu.dsp_type}"
            data-cid="${menu.cid}">`;
            html += `<div class="dd-handle"><div class="menu-title">${menu.name}</div></div>`;
            html += `<a data-toggle="collapse" data-parent="#accordion" href="#collapse${menu.cid}" aria-expanded="true" class="pull-right-container menu-pull-btn">
            <i class="fa fa-caret-down pull-right"></i>
            <span class="pull-right menu-type">${(menu.type == 'category') ? 'Danh mục' : 'Liên kết tự tạo'}</span>
            </a>`;
            html += `<div id="collapse${menu.cid}" class="panel-collapse collapse" aria-expanded="false" style="">
                <div class="menuitem-option">
                    <div class="form-group menu-from-wrapper">
                        <div class="form-input">
                            <label for="option-item-name">
                                Nhãn điều hướng
                                <input type="text" name="name_${menu.id}" class="option-input-name" value="${menu.name}"/>
                            </label>
                        </div>
                        <div class="form-input">
                            <label for="option-item-url">
                                URL
                                <input type="text" name="url_${menu.id}" class="option-input-name" value="${menu.url}"/>
                            </label>
                        </div>
                        <input type="hidden" name="id_${menu.id}" value="${menu.id}" />
                        <div class="form-input">
                            <div class="option-action">
                                <span class="option-btn option-cancel" ng-click="deleteItem(${menu.id})">Xoá chỉ mục này</span>
                                <span class="option-btn option-save" ng-click="saveChange(${menu.id})">Lưu thay đổi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            if (menu.children && menu.children.length > 0) {
                html += $scope.buildHtmlMenu(menu.children);
            }
            html += '</li>';
        });
        html += '</ol>';
        return html;
        // return $sce.trustAsHtml(html);
    }

    /**
     * Add checked list category to menu.
     */
    $scope.addCategoryToMenu = function () {
        var tempMenu = getStorageMenu();
        var rebuild = false;
        if (tempMenu.length <= 0) {
            rebuild = true;
        }
        var loopData = $scope.allCategories;
        if ($scope.categoryMode == 'searchCate') {
            loopData = $scope.searchCategories;
        }
        var d = new Date();
        angular.forEach(loopData, function (item) {
            if (item.checked) {
                var itemMenu = {
                    id: item.id,
                    cid: (d.getTime() + item.id),
                    parent_id: item.parent_id,
                    name: item.name,
                    is_hidden: 0,
                    slug: item.slug,
                    type: "category",
                    dsp_type: "normal",
                    url: `/${item.slug}-c${item.id}.html`,
                    children: []
                };
                tempMenu.push(itemMenu);
            }
        });
        resetToggleCategory();
        if (rebuild) {
            tempMenu = tempMenu.reverse();
            tempMenu = buildTreeMenu(tempMenu);
        }
        $scope.menuHtml = $scope.buildHtmlMenu(tempMenu);
        $scope.menuSetting = tempMenu;
        setStorageMenu(tempMenu);
        tempMenu = [];
    }

    /**
     * Add custom URL to menu
     */
    $scope.addCustomLinkToMenu = function () {
        var tempMenu = getStorageMenu();
        if ($scope.custom.name != '' && $scope.custom.url != '') {
            var d = new Date();
            var itemMenu = {
                id: d.getTime(),
                cid: -1,
                parent_id: -1,
                name: $scope.custom.name,
                is_hidden: 0,
                slug: "",
                type: "custom",
                dsp_type: "normal",
                url: $scope.custom.url,
                children: []
            };
            $scope.custom.name = "";
            $scope.custom.url = "";
            tempMenu.push(itemMenu);
            $scope.menuHtml = $scope.buildHtmlMenu(tempMenu);
            $scope.menuSetting = tempMenu;
            setStorageMenu(tempMenu);
            tempMenu = [];
        } else {
            toastr.error("Vui lòng nhập đầy đủ thông tin vào liên kết tự tạo.");
        }
    }

    /**
     * Toggle checked all category
     */
    $scope.toggleAllCategory = function () {
        var loopData = $scope.allCategories;
        $scope.isToggleAll = !$scope.isToggleAll;
        if ($scope.categoryMode == 'searchCate') {
            loopData = $scope.searchCategories;
        }
        angular.forEach(loopData, function (category, index) {
            category.checked = $scope.isToggleAll ;
        });
    }

    /**
     * Send request to save menu 
     */
    $scope.saveMenu = function () {
        var storageMenu = getStorageMenu();
        if ( storageMenu.length <= 0 ) {
            storageMenu = $scope.menuSetting;
        }
        var optionId = $scope.optionMenu.id;
        var endpoint = `/option/${optionId}`;
        var updateParams = {
            "menu": storageMenu,
            "option_auto": $scope.option.auto
        }
        var url = $scope.buildUrl(endpoint);
        $http.patch(url, { value: JSON.stringify(updateParams) })
            .then(response => {
                if (response.data.status === 'successful') {
                    $scope.menuHtml = $scope.buildHtmlMenu(storageMenu);
                    toastr.success("Thành công!");
                }
            })
    }

    /**
     * Clear list menu config.
     * 
     */
    $scope.deleteMenu = function () {
        var check = confirm('Bạn có chắc muốn làm rỗng menu này không?');
        if (check) {
            // $scope.showLoading();
            var menuSetting = [];
            var optionId = $scope.optionMenu.id
            var url = $scope.buildUrl(`/option/${optionId}`);
            var updateParams = {
                "menu": menuSetting,
                "option_auto": $scope.option.auto
            };
            $http.patch(url, { value: JSON.stringify(updateParams) })
                .then(response => {
                    //  $scope.hideLoading();
                    if (response.data.status == 'successful') {
                        toastr.success('Xoá thành công');
                        $scope.menuSetting = menuSetting;
                        setStorageMenu($scope.menuSetting);
                        $scope.menuHtml = $scope.buildHtmlMenu([]);
                    } else {
                        toastr.error('Có lỗi trong quá trình xử lý');
                    }
                })
        }
    }

    /**
     * Save menu item changed
     * exp: change name or change url
     */
    $scope.saveChange = function (id) {
        // $scope.menuSetting = getStorageMenu();
        var isSave = true;
        var optionName = $('.menuitem-option input[name=name_' + id + ']').val();
        var optionUrl = $('.menuitem-option input[name=url_' + id + ']').val();
        if ( optionName == '') {
            toastr.error('Nhãn điều hướng không được trống.');
            isSave = false;
        }
        if ( isSave ) {
            var item = {
                id: id,
                name: optionName,
                url: optionUrl
            };
            var keepGoing = true;
            angular.forEach($scope.menuSetting, function (val, i) {
                if (keepGoing) {
                    if (val.id == item.id) {
                        $scope.menuSetting[i].name = item.name;
                        $scope.menuSetting[i].url = item.url;
                        keepGoing = false;
                    } else {
                        var rs = findRecursive(val, item, keepGoing);
                        $scope.menuSetting[i] = rs.data;
                        keepGoing = rs.keep;
                    }
                }
            });
            setStorageMenu($scope.menuSetting);
            $scope.menuHtml = '';
            $scope.saveMenu();
        }
    }

    /**
     * Delete item of list menu
     * if item has children, it will be move to root.
     */
    $scope.deleteItem = function (id) {
        var keepGoing = true;
        var temp = [];
        angular.forEach($scope.menuSetting, function (val, i) {
            if (keepGoing) {
                if (val.id == id) {
                    if (val.children && val.children.length > 0) {
                        temp = val.children;
                    }
                    $scope.menuSetting.splice(i, 1);
                    keepGoing = false;
                } else {
                    var rs = deleteMenuItem(val, id, keepGoing);
                    keepGoing = rs.keep;
                    temp = rs.temp;
                }
            }
        });
        if (temp.length > 0) {
            angular.forEach(temp, function (item) {
                $scope.menuSetting.push(item);
            })
        }
        setStorageMenu($scope.menuSetting);
        $scope.menuHtml = $scope.buildHtmlMenu($scope.menuSetting);
        $scope.saveMenu();
    }

    /**
     * Search category for add to menu
     */
    $scope.searchCategory = function () {
        $scope.isSearching = true;
        var searchTitle = $scope.search.category;
        var url = $scope.buildUrl(`/category?filters=name~${searchTitle}`);
        $http.get(url)
            .then(response => {
                if (response.data.status == 'successful') {
                    var result = response.data.result;
                    $scope.searchCategories = result;
                    $scope.isSearching = false;
                }
            })
    }

    /**
     * Change mode when click tab of category section
     * exp: All categories tab or search category tab
     */
    $scope.setMode = function (mode) {
        resetToggleCategory();
        $scope.categoryMode = mode;
    }

    /**
     * Auto create config menu if not exists
     */
    $scope.createConfigNotExist = function () {
        var url = $scope.buildUrl('/option');
        var configValue = {
            "menu": [],
            "option_auto": true
        };
        var requestParams = {
            name: 'Config general menu',
            key: 'general.menu',
            value: JSON.stringify(configValue)
        };

        $http.post(url, requestParams)
            .then(response => {
                if (response.data.status == 'successful' && response.data.result) {
                    var result = response.data.result;
                    $scope.optionMenu = response.data.result;
                    var resultValue = JSON.parse(result.value);
                    if (typeof resultValue.menu !== 'undefined ') {
                        $scope.menuSetting = resultValue.menu;
                    }
                    if (typeof resultValue.option_auto !== 'undefined') {
                        $scope.option.auto = resultValue.option_auto;
                    }
                    setStorageMenu($scope.menuSetting)
                    $scope.menuHtml = $scope.buildHtmlMenu($scope.menuSetting);
                }
            })
    }

    /**
     * Find menu item of childrens and remove
     * @param {Array} data 
     * @param {Integer} id 
     * @param {Boolean} keepGoing 
     */
    function deleteMenuItem(data, id, keepGoing) {
        var temp = [];
        angular.forEach(data.children, function (n, i) {
            if (keepGoing) {
                if (n.id == id) {
                    if (n.children && n.children.length > 0) {
                        temp = n.children;
                    }
                    data.children.splice(i, 1);
                    keepGoing = false;
                } else {
                    var rs = deleteMenuItem(n, id, keepGoing);
                    temp = rs.temp;
                    keepGoing = rs.keep;
                }
            }
        });
        return { temp: temp, keep: keepGoing }
    }

    /**
     * Reset all checked categories after add to menu
     */
    function resetToggleCategory() {
        var loopData = $scope.allCategories;
        if ($scope.categoryMode == 'searchCate') {
            loopData = $scope.searchCategories;
        }
        angular.forEach(loopData, function (val, i) {
            val.checked = false;
        });
    }

    function rebuildCategory(data) {
        data.forEach(function (item) {
            item.checked = false;
        });
        return data;
    }

    function buildTreeMenu(data) {
        var treeMenu = [];
        angular.forEach(data, function (item, index) {
            var foundParent = false;
            if (item.cid != 0) {
                angular.forEach(treeMenu, function (n, i) {
                    var checkDuplicate = findDuplicate(n.children, item.id);
                    if (n.id == item.parent_id && checkDuplicate) {
                        n.children.push(item);
                        foundParent = true;
                    } else {
                        foundParent = recursive(n, item, foundParent);
                    }
                });
                if (!foundParent) {
                    treeMenu.push(item);
                }
            } else {
                treeMenu.push(item);
            }
        });
        return treeMenu;
    }

    function recursive(n, item, foundParent) {
        angular.forEach(n.children, function (n, i) {
            var checkDuplicate = findDuplicate(n.children, item.id);
            if (n.id == item.parent_id && checkDuplicate) {
                n.children.push(item);
                foundParent = true;
            } else {
                foundParent = recursive(n, item, foundParent);
            }
        });
        return foundParent;
    }

    function findRecursive(n, item, keepGoing) {
        angular.forEach(n.children, function (v, i) {
            if (keepGoing) {
                if (v.id == item.id) {
                    n.children[i].name = item.name;
                    n.children[i].url = item.url;
                    keepGoing = false
                } else {
                    var rs = findRecursive(v, item, keepGoing);
                    n[i] = rs.data;
                    keepGoing = rs.keep;
                }
            }
        });
        return { keep: keepGoing, data: n };
    }

    /**
     * Get menu from storage and Parse to json.
     */
    function getStorageMenu() {
        var tempItem = localStorage.getItem("jsonmenu");
        var retval = [];
        try {
            retval = JSON.parse(tempItem);
        } catch (err) {
            console.error('localStorage menu error', err);
        }
        return retval;
    }

    /**
     * Parse array object to json and save to localStorage
     * @param {Array} data 
     */
    function setStorageMenu(data) {
        localStorage.setItem('jsonmenu', JSON.stringify(data));
    }

    /**
     * Check dupplicate menu item when build.
     * @param {Array} data 
     * @param {Integer} id 
     */
    function findDuplicate(data, id) {
        var retval = true;
        if (data && data.length > 0) {
            angular.forEach(data, function (val, key) {
                if (val.id == id) {
                    retval = false;
                }
            });
        }
        return retval;
    }


    $scope.initialize();
}