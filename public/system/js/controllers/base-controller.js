system.controller('BaseController', BaseController);

function BaseController($scope, $http, $rootScope, Upload) {
    $scope.filters = {};
    $scope.meta = [];
    $scope.sorts = {
        field: '',
        type: ''
    };
    $scope.displayedFilters = {};

    var VIETNAMESE_N_ASCII_MAP = {
        "à": "a", "ả": "a", "ã": "a", "á": "a", "ạ": "a", "ă": "a", "ằ": "a", "ẳ": "a", "ẵ": "a",
        "ắ": "a", "ặ": "a", "â": "a", "ầ": "a", "ẩ": "a", "ẫ": "a", "ấ": "a", "ậ": "a", "đ": "d",
        "è": "e", "ẻ": "e", "ẽ": "e", "é": "e", "ẹ": "e", "ê": "e", "ề": "e", "ể": "e", "ễ": "e",
        "ế": "e", "ệ": "e", "ì": 'i', "ỉ": 'i', "ĩ": 'i', "í": 'i', "ị": 'i', "ò": 'o', "ỏ": 'o',
        "õ": "o", "ó": "o", "ọ": "o", "ô": "o", "ồ": "o", "ổ": "o", "ỗ": "o", "ố": "o", "ộ": "o",
        "ơ": "o", "ờ": "o", "ở": "o", "ỡ": "o", "ớ": "o", "ợ": "o", "ù": "u", "ủ": "u", "ũ": "u",
        "ú": "u", "ụ": "u", "ư": "u", "ừ": "u", "ử": "u", "ữ": "u", "ứ": "u", "ự": "u", "ỳ": "y",
        "ỷ": "y", "ỹ": "y", "ý": "y", "ỵ": "y", "À": "A", "Ả": "A", "Ã": "A", "Á": "A", "Ạ": "A",
        "Ă": "A", "Ằ": "A", "Ẳ": "A", "Ẵ": "A", "Ắ": "A", "Ặ": "A", "Â": "A", "Ầ": "A", "Ẩ": "A",
        "Ẫ": "A", "Ấ": "A", "Ậ": "A", "Đ": "D", "È": "E", "Ẻ": "E", "Ẽ": "E", "É": "E", "Ẹ": "E",
        "Ê": "E", "Ề": "E", "Ể": "E", "Ễ": "E", "Ế": "E", "Ệ": "E", "Ì": "I", "Ỉ": "I", "Ĩ": "I",
        "Í": "I", "Ị": "I", "Ò": "O", "Ỏ": "O", "Õ": "O", "Ó": "O", "Ọ": "O", "Ô": "O", "Ồ": "O",
        "Ổ": "O", "Ỗ": "O", "Ố": "O", "Ộ": "O", "Ơ": "O", "Ờ": "O", "Ở": "O", "Ỡ": "O", "Ớ": "O",
        "Ợ": "O", "Ù": "U", "Ủ": "U", "Ũ": "U", "Ú": "U", "Ụ": "U", "Ư": "U", "Ừ": "U", "Ử": "U",
        "Ữ": "U", "Ứ": "U", "Ự": "U", "Ỳ": "Y", "Ỷ": "Y", "Ỹ": "Y", "Ý": "Y", "Ỵ": "Y"
    };

    var PREFIX_CACHE_FILTER = 'cache_filter_';

    $scope.locations = [];

    $scope.buildFilterUrl = function (url) {
        var hasParam = url.split('?')[1] !== undefined;
        if (Object.keys($scope.filters).length > 0) {
            if (!hasParam) {
                url += '?filters=';
            } else {
                url += '&filters=';
            }
            for (key in $scope.filters) {
                if (typeof ($scope.filters[key]) === 'object') {
                    if ($scope.filters[key].value != '') {
                        url += key + $scope.filters[key].operator + $scope.filters[key].value + ',';
                    }
                } else {
                    if ($scope.filters[key] != '') {
                        url += key + '=' + $scope.filters[key] + ',';
                    }
                }
            }
        }

        return url;
    }

    $scope.buildSortUrl = function (url) {
        var hasParam = url.split('?')[1] !== undefined;
        if ($scope.sorts.field !== '') {
            if (!hasParam) {
                url += '?sorts=';
            } else {
                url += '&sorts=';
            }
            url += ($scope.sorts.type === 'asc' ? '' : '-') + $scope.sorts.field + ',';
        }

        return url;
    }

    $scope.formatPrice = function (price) {
        var formatter = new Intl.NumberFormat({
            maximumSignificantDigits: 2
        });

        return formatter.format(price);
    }

    $scope.formatCurrency = function(num) {
        if (num != parseFloat(num)) {
            num = 0;
        }
        var newstr = '';
        num = parseFloat(num);
        var p = num.toFixed(2).split(".");
        var chars = p[0].split("").reverse();
        var count = 0;
        for (x in chars) {
            count++;
            if (count % 3 == 1 && count != 1) {
                newstr = chars[x] + '.' + newstr;
            } else {
                newstr = chars[x] + newstr;
            }
        }

        return newstr;
    }

    $scope.toFriendlyString = function (originalString) {
        if (originalString == null || originalString.length == 0) {
            return originalString;
        }
        //ELSE:
        var removedDuplicatedSpacesString = originalString.replace(/\s+/g, " ");
        var removedVietnameseCharsString = "";
        for (var idx = 0; idx < removedDuplicatedSpacesString.length; idx++) {
            var ch = removedDuplicatedSpacesString[idx];
            var alternativeChar = VIETNAMESE_N_ASCII_MAP[ch];
            if (alternativeChar != null) {
                removedVietnameseCharsString += alternativeChar;
            } else {
                removedVietnameseCharsString += ch;
            }
        }
        return removedVietnameseCharsString.toLowerCase()
            .replace(/[^0-9a-zA-Z]/g, "-")
            .replace(/\-+/g, "-");
    };

    $scope.summarizeDateTime = function (dateTime, withYear) {
        if (dateTime != null) {
            var outputFormat = "$3/$2";
            if (withYear) {
                outputFormat += "/$1";
            }
            outputFormat += " $4:$5";
            return dateTime.replace(/(\d{4})-(\d{2})-(\d{2})\s+(\d{1,2}):(\d{1,2}):.*/, outputFormat);
        }
    };

    $scope.summarizeDate = function (dateTime, withYear) {
        if (dateTime != null) {
            var outputFormat = "$3/$2";
            if (withYear) {
                outputFormat += "/$1";
            }
            return dateTime.replace(/(\d{4})-(\d{2})-(\d{2})\s+(\d{1,2}):(\d{1,2}):.*/, outputFormat);
        }
    };

    $scope.summarizeDateFromVietnameseTime = function (dateTime, withYear) {
        if (dateTime != null) {
            var outputFormat = "$1/$2";
            if (withYear) {
                outputFormat += "/$3";
            }
            return dateTime.replace(/(\d{2})\/(\d{2})\/(\d{4})\s+(\d{1,2}):(\d{1,2}):.*/, outputFormat);
        }
    };

    $scope.summarizeTime = function (dateTime) {
        if (dateTime != null) {
            return dateTime.replace(/\d{4}-(\d{2})-(\d{2})\s+(\d{1,2}):(\d{1,2}):.*/, "$3:$4");
        }
    };

    $scope.toVietnameseDate = function (dateTime, withYear) {
        if (dateTime != null && dateTime.length > 0) {
            var outputFormat = "$3/$2";
            if (withYear) {
                outputFormat += "/$1";
            }
            return dateTime.replace(/(\d{4})-(\d{2})-(\d{2})(?:\s+(\d{2}):(\d{2}):.*)?/, outputFormat);
        } else {
            return "";
        }
    };

    $scope.vietnameseDateToTimestamp = function (dateString) {
        var retVal = null;
        if (!$scope.isValidVietnameseDate(dateString)) {
            return retVal;
        }
        //ELSE:
        var dateParts = dateString.match(/^\s*(\d{2})\/(\d{2})\/((?:19|20)\d{2})\s*$/);
        var day = dateParts[1];
        var month = dateParts[2];
        var year = dateParts[3];
        var date = new Date(year, month - 1, day);
        retVal = date.getTime() / 1000;
        //return
        return retVal;
    };

    $scope.vietnameseTimeToSQLTime = function (dateString) {
        var retVal = null;
        return dateString.replace(/(\d{2})\/(\d{2})\/(\d{4})\s+(\d{1,2}):(\d{1,2}):(\d{1,2}).*/, "$3-$2-$1 $4:$5:$6");
    };

    $scope.isValidVietnameseDate = function (dateString) {
        var retVal = false;
        if (dateString == null || dateString.length != 10) {
            return retVal;
        }
        //ELSE:
        var dateParts = dateString.match(/^\s*(\d{2})\/(\d{2})\/((?:19|20)\d{2})\s*$/);
        if (dateParts == null || dateParts.length != 4) {
            return retVal;
        }
        //ELSE:
        var day = dateParts[1];
        var month = dateParts[2];
        var year = dateParts[3];
        var date = new Date(year, month - 1, day);
        var b = date.getMonth() + 1 != month
            || date.getFullYear() != year
            || date.getDate() != day;
        retVal = !b;
        //return
        return retVal;
    };

    $scope.moneyToString = function (price) {
        if (price == null || price.toString().match(/^\-?[0-9]+(\.[0-9]+)?$/) == null) {
            return "NA";
        }
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    $scope.extractLocation = function (address) {
        var retVal = null;
        if (address == null || address.length == 0) {
            return retVal;
        }
        //ELSE:
        var friendlyAddress = $scope.toFriendlyString(address);
        var currentScore = -1;
        for (var idx = 0; idx < $scope.locations.length; idx++) {
            var location = $scope.locations[idx];
            for (var idx2 = 0; idx2 < location.keywords.length; idx2++) {
                var keyword = location.keywords[idx2];
                var score = friendlyAddress.lastIndexOf(keyword);
                if (score != -1 && score > currentScore) {
                    retVal = location;
                    currentScore = score;
                }
            }
        }
        //return
        return retVal;
    };

    $scope.isEmptyString = function (string) {
        var regex = /^\s*$/;
        var retVal = string == null || (typeof string.match == "function" && string.match(regex) != null);
        return retVal;
    };

    $scope.isValidEmail = function (email) {
        var regex = /^[\w\.]+@[\w\.]+\.\w{2,5}$/;
        var retVal = email != null && email.match(regex) != null;
        return retVal;
    };

    $scope.isValidPhone = function (phone) {
        if (phone == null) {
            return false;
        }
        //ELSE:
        var stdPhone = $scope.standardizePhone(phone);
        var regex = /^0(9\d{8}|1\d{9}|[2345678]\d{7,14})$/;
        return stdPhone.match(regex) != null;
    };

    $scope.standardizePhone = function (phone) {
        if (phone == null) {
            return phone;
        }
        //ELSE:
        return phone.replace(/[^0-9]/g, "");
    };

    $scope.formatPhone = function (phone) {
        if (phone == null) {
            return phone;
        }
        var stdPhone = $scope.standardizePhone(phone);
        return stdPhone.replace(/^(\d+)(\d{3})(\d{3})$/, "$1-$2-$3");
    };

    $scope.setPageTitle = function (title) {
        if (title == null) {
            $("title").text("Hello, this's Chiaki System Admin");
        } else {
            $("title").text(title);
        }
    };

    $scope.getByCode = function (list, code) {
        var retVal = null;
        list.forEach(function (item) {
            if (item.code == code) {
                retVal = item;
            }
        });
        return retVal;
    };

    $scope.getByField = function (list, fieldName, value) {
        var retVal = null;
        list.forEach(function (item) {
            if (item[fieldName] == value) {
                retVal = item;
            }
        });
        return retVal;
    };

    $scope.subSring = function (string, subLength) {
        if (typeof string === 'undefined' || string === null || string === '') {
            return '';
        }
        var subString = string.substring(0, subLength);
        if (string.length > subString.length) {
            subString = subString + '...';
        }

        return subString;
    };

    this.getKeyCode = function (event) {
        var retVal = event.which == 0 ? event.keyCode : event.which;
        return retVal;
    };

    $scope.formatFloat = function (floatValue, decimalDigitsCount) {
        var retVal = "";
        var intValue = Math.floor(floatValue);
        retVal += $scope.formatInt(intValue);
        if (decimalDigitsCount > 0) {
            retVal += ",";
            var parts = ("" + floatValue).split(".");
            var decimalPart = (parts.length == 2) ? parts[1] : "";
            for (var idx = 0; idx < decimalDigitsCount; idx++) {
                if (idx >= decimalPart.length) {
                    retVal += "0";
                } else {
                    retVal += decimalPart[idx];
                }
            }
        }
        return retVal;
    };

    $scope.formatInt = function (intValue) {
        return Math.floor(intValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    $scope.timestampToDate = function (time) {
        var retVal = "";
        var d = new Date(time);
        retVal = (d.getDate() + 100 + " ").substring(1, 3) + '/' + (d.getMonth() + 101 + " ").substring(1, 3) + '/' + d.getFullYear();
        return retVal;
    };

    $scope.timestampToTime = function (totalSeconds) {
        var days = Math.floor(totalSeconds / 86400);
        var hours = Math.floor((totalSeconds - (days * 86400)) / 3600);
        var minutes = Math.floor((totalSeconds - (days * 86400) - (hours * 3600)) / 60);
        var seconds = totalSeconds - (days * 86400) - (hours * 3600) - (minutes * 60);

        // round seconds
        seconds = Math.round(seconds * 100) / 100;
        var result = '';
        if (days > 0) {
            result = (days < 10 ? "0" + days + " ngày" : days + " ngày");
            result += ", " + (hours < 10 ? "0" + hours + " giờ" : hours + " giờ");
        } else {
            result += (hours < 10 ? "0" + hours + " giờ" : hours + " giờ");
        }
        result += ", " + (minutes < 10 ? "0" + minutes + " phút" : minutes + " phút");
//        result += ", " + (seconds < 10 ? "0" + seconds + " giây" : seconds + " giây");
        return result;
    };

    this.roundingInt = function (integerValue, zerosCount) {
        var retVal = integerValue;
        var stdZerosCount = zerosCount != null ? zerosCount : 3;
        var roundingValue = Math.pow(10, stdZerosCount);
        var oddPart = integerValue % roundingValue;
        retVal -= oddPart;
        if (oddPart >= roundingValue / 2) {
            retVal += roundingValue;
        }
        return retVal;
    };

    this.roundingFloat = function (floatValue, decimalDigitsCount) {
        var valueParts = ("" + floatValue).split(".");
        if (valueParts.length != 1 && valueParts.length != 2) {
            return "NA";
        }
        //ELSE:
        var retVal = parseInt(valueParts[0]);
        if (decimalDigitsCount == 0 || valueParts.length == 1) {
            return retVal;
        }
        //ELSE:
        var decimalPart = valueParts[1];
        var stdDecimalPart = decimalPart.substring(0, decimalDigitsCount);
        if (decimalPart.length > decimalDigitsCount
            && parseInt(decimalPart[decimalDigitsCount]) >= 5) {
            stdDecimalPart = "" + (parseInt(stdDecimalPart) + 1);
        }
        var retVal = parseFloat(valueParts[0] + "." + stdDecimalPart);
        return retVal;
    };

    this.parseFloat = function (value) {
        if (Number(value) === value) {
            return value;
        }
        var valueParts = getNumberValueParts(value);
        if (valueParts.length != 1 && valueParts.length != 2) {
            return "NA";
        }
        //ELSE:
        var integerPart = valueParts[0];
        var retVal = parseInt(integerPart);
        if (valueParts.length == 2) {
            var decimalPart = valueParts[1];
            retVal += parseInt(decimalPart) / Math.pow(10, decimalPart.length);
        }
        return retVal;
    };

    this.parseInt = function (string) {
        if (Number(string) === string) {
            if (string % 1 === 0) {
                return string;
            } else {
                return Math.floor(string);
            }
        }
        var valueParts = getNumberValueParts(string);
        if (valueParts.length != 1 && valueParts.length != 2) {
            return "NA";
        }
        //ELSE:
        var retVal = valueParts[0];         //return
        return retVal;
    };

    /**
     * is valid integer or decimal number
     * @param {type} value
     * @returns {undefined}
     */
    $scope.isValidNumber = function (value) {
        if (!isNaN(value)) {
            return true;
        }
        //ELSE:
        var retVal = false;
        var valueParts = getNumberValueParts(value);
        if (valueParts.length == 1 || valueParts.length == 2) {
            retVal = true;
        }
        //return
        return retVal;
    };

    $scope.upperCaseFirstLetter = function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $scope.lowerCaseFirstLetter = function (string) {
        return string.charAt(0).toLowerCase() + string.slice(1);
    }

    $scope.showSuccessModal = function (title, callback) {
        swal({
            title: "Thành công",
            text: $scope.upperCaseFirstLetter(title) + " thành công!",
            type: "success"
        }).then(function () {
            if(callback){
                callback();
            }
        });
    }

    $scope.showErrorModal = function (title, errorMeg = null) {
        if(errorMeg) {
            swal({
                title: "Lỗi",
                text: errorMeg,
                type: "error"
            });

        }else{
            swal({
                title: "Lỗi",
                text: "Không thể " + $scope.lowerCaseFirstLetter(title),
                type: "error"
            });
        }


    }

    $scope.callConfirmModal = function (param, callback) {
        swal({
            title: $scope.upperCaseFirstLetter(param.title),
            html: '<span style="font-size: 1.5em; color: #545454; font-weight: 300;">Bạn có chắc chắn muốn ' +
                param.text +
                " không?<span>",
            type: "warning",
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: "Có",
            cancelButtonText: "Không"
        }).then(function (isConfirm) {
            if (isConfirm.value) {
                callback(param.arg);
            }
        });
    }

    $scope.callConfirmDeleteModal = function (param) {
        $scope.callConfirmModal({
            title: param.title,
            text: param.text
        }, function () {
            $http.delete(param.url)
                .then(function (result) {
                    if (param.success) {
                        param.success(result);
                    }
                    $scope.showSuccessModal(param.title);
                })
                .catch(function (error) {
                    if (param.error) {
                        param.error(error);
                    }

                    let meg = null;
                    if(error.data.message){
                        meg = error.data.message;
                    }

                    $scope.showErrorModal(param.title, meg);
                });
        });
    }

    $scope.callConfirmUpdateModal = function (param) {
        $scope.callConfirmModal({
            title: param.title,
            text: param.text
        }, function () {
            $http.put(param.url, param.data)
                .then(function (result) {
                    if (param.success) {
                        param.success(result);
                    }
                    $scope.showSuccessModal(param.title);
                })
                .catch(function (error) {
                    if (param.error) {
                        param.error(error);
                    }

                    let meg = null;
                    if(error.data.message){
                        meg = error.data.message;
                    }

                    $scope.showErrorModal(param.title, meg);
                });
        });
    };

    $scope.buildDeleteUrl = function (url, ids) {
        url = url + '?filters=id={';
        for (var i = 0; i < ids.length; i++) {
            url += ids[i];
            if (i < ids.length - 1) {
                url += ';';
            }
        }
        url += '}';

        return url;
    }

    this.initialize = function (callback) {
    };

    this.openDialogModal = function (selector) {
        $(selector).modal({
            modal: true,
            persist: true,
            position: [30, 0],
            autoPosition: true
        });
    };

    this.addListenHiddenModal = function (selector, callback) {
        $(selector).on('hidden.bs.modal', callback);
    };

    this.addListenHiddenModal = function (selector, callback) {
        $(selector).on('hidden.bs.modal', callback);
    };

    this.loaddingButton = function (selector, text = 'Đang xử lý...') {
        $(selector).attr('dt-text', $(selector).text());
        $(selector).text(text);
        $(selector).attr("disabled", true);
    }

    this.stopLoaddingButton = function (selector) {
        $(selector).text($(selector).attr('dt-text'));
        $(selector).attr("disabled", false);
    }

    $scope.loaddingButton = function (selector) {
        $(selector).attr('dt-text', $(selector).text());
        $(selector).text('Loading...');
        $(selector).attr("disabled", true);
    }

    $scope.stopLoaddingButton = function (selector) {
        $(selector).text($(selector).attr('dt-text'));
        $(selector).attr("disabled", false);
    }
    $scope.findIndexByKeyValue = function (arrayToSearch, key, valueToSearch) {
        for (var i = 0; i < arrayToSearch.length; i++) {
            if (arrayToSearch[i][key] == valueToSearch) {
                return i;
            }
        }
    }


    $scope.addFilterInRange = function (min, max, fieldName, type) {
        var minFilter = min;
        var minDisplay = min;
        var maxFilter = max;
        var maxDisplay = max;
        if (type === 'date') {
            minFilter = min ?  $scope.jsDatetimeToSqlDate(min) : '';
            minDisplay = min ? $scope.timestampToDate(min) : '';
            maxFilter = max ? $scope.jsDatetimeToSqlDate(max) : '';
            maxDisplay = max ? $scope.timestampToDate(max): '';
        } else {
            minDisplay = $scope.formatCurrency(min);
            maxDisplay = $scope.formatCurrency(max);
        }

        if (type !== '' && min && max) {
            $scope.filters[fieldName] = {
                operator: "=",
                value: "[" + minFilter + ";" + maxFilter + "]"
            };
            $scope.displayedFilters[fieldName] = "Từ " + minDisplay + " Đến " + maxDisplay;
        } else {
            if (min) {
                $scope.filters[fieldName] = {
                    operator: ">=",
                    value: minFilter
                };
                $scope.displayedFilters[fieldName] = "Từ " + minDisplay;
            } else {
                if (max) {
                    $scope.filters[fieldName] = {
                        operator: "<=",
                        value: maxFilter
                    };
                    $scope.displayedFilters[fieldName] = "Đến " + maxDisplay;
                }
            }
        }
    }

    $scope.jsDatetimeToSqlDatetime = function (dateTime) {
        var date = new Date(dateTime);
        var tzoffset = date.getTimezoneOffset() * 60000; //offset in milliseconds
        var mySqlDT = (new Date(date.getTime() - tzoffset)).toISOString().slice(0, 19).replace('T', ' ');

        return mySqlDT;
    }

    $scope.jsDatetimeToSqlDate = function (dateTime) {
        var date = new Date(dateTime);
        var tzoffset = date.getTimezoneOffset() * 60000; //offset in milliseconds
        var mySqlDT = (new Date(date.getTime() - tzoffset)).toISOString().slice(0, 10);

        return mySqlDT;
    }
    $scope.getDisplayedFieldName = function (key)
    {
        var result = '';
        if ($scope.displayedFieldName === undefined) {
            result = key;
        } else {
            if ($scope.displayedFieldName[key] !== undefined) {
                result = $scope.displayedFieldName[key];
            } else {
                result = key;
            }
        }

        return result;
    }

    $scope.getImageCdn = function ($url, $width = 0, $height = 0, $fitIn = true) {
            // return 0;
            if (!$url) return "";
            let retval;
            let originUrl = $url;
            if (originUrl.substr(0, 4) == 'http') {
                $url = $url.replace('https://', '');
                $url = $url.replace('http://', '');
            }
            // return $url;

            retval =  "https://cdn.shopbay.vn/unsafe/" + $url;

            if ($width == 0 && $height == 0) {
                retval =  "https://cdn.shopbay.vn/unsafe/" + $url;
            } else if ($width == 0 || $height == 0) {
                retval = "https://cdn.shopbay.vn/unsafe/" + $width + "x" + $height + "/" + $url;
            } else {
                retval = "https://cdn.shopbay.vn/unsafe/fit-in/" + $width + "x" + $height + "/filters:fill(fff)/" + $url;
            }
            return retval;
    }

    $scope.cacheFilter = function (type, value) {
        if (typeof(Storage) !== 'undefined') {
            localStorage.setItem(PREFIX_CACHE_FILTER + type, value);
        } else {
            console.log('Trình duyệt của bạn không hỗ trợ Storage');
        }
    }

    $scope.getCacheFilter = function (type) {
        return localStorage.getItem(PREFIX_CACHE_FILTER + type);
    }

    $scope.initOption = function () {
        toastr.options = {
            "autoDismiss": true,
            "preventDuplicates": true,
            "debug": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "fadeIn": 300,
            "fadeOut": 1000,
            "timeOut": 3000,
            "extendedTimeOut": 1000
        };
    }

    $scope.navigate = function (url) {
        window.location.href = url;
    }

    $scope.upload = function (file, callback) {
        if (typeof file == 'array') {
            file = file[0];
        }
        return new Promise(function(resolve, reject) {
            Upload.upload({
                url: 'https://s3.shopbay.vn/upload',
                data: {upload: file}
            }).then(function (resp) {
                if (resp.data.status == 'successful') {
                    let upload = resp.data.upload[0];
                    resolve(upload);
                } else {
                    reject(0);
                }
            }, function (resp) {
                reject(0);
            }, function (evt) {
                let progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.progressPercentage = progressPercentage;
            });
        });

    };

    $scope.uploads = function (files) {
        return new Promise(function(resolve, reject) {
            Upload.upload({
                url: 'https://s3.shopbay.vn/upload',
                data: {upload: files},
            }).then(function (resp) {
                if (resp.data.status == 'successful') {
                    let upload = resp.data.upload;
                    resolve(upload);
                } else {
                    reject(0);
                }
            }, function (resp) {
                reject(0);
            }, function (evt) {
                let progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.progressPercentage = progressPercentage;
            });
        });
    };

    $scope.initOption();
}
