/**
 * Created by Arty on 22.05.2016.
 */

;(function($, undefined){

    var $wrapper;
    var settings
    var jWrapId;
    var allOperatorsCount = 0;
    var tPager = null;

    var showFade = function(){

        if( !$.toInt($("body div.xtourism-ajax-loader").length) ) {
            $("body").append('<div class="xtourism-ajax-loader xtourism-default xtourism-spinner-load0"><div class="xtourism-spinner xtourism-big-spinner"></div><div class="xtourism-spinner-label">Загружаем туры</div></div>');
        }

        if( !$( "body div.xtourism-ajax-loader").is(":visible") ) {
            $("body div.xtourism-ajax-loader").show();
        }
    }

    var hideFade = function(){

        if( $.toInt($("body div.xtourism-ajax-loader").length) ) {
            $( "body div.xtourism-ajax-loader" ).fadeOut("hide", function(){$(this).remove();});
        }
    }

    var getToursCount = function(count){
        switch ($.endingNumberType(count)){
            case 1: return count + " " + "тур";
            case 2: return count + " " + "тура";
            case 3: return count + " " + "туров";
        }
    }

    window._TResult = function(_wrapperId, operators_count, _settings) {

        settings = _settings;
        allOperatorsCount = operators_count;
        jWrapId = "div#" + _wrapperId;
        $wrapper = $("#" + _wrapperId);

        var scrollToResult = function () {
            $(window).scrollTop($("#resultToursSearch", $wrapper).offset().top - 35);
        }

        this.setOperatorsCount = function(operators_count){
            allOperatorsCount = operators_count;
        }

        this.runSearch = function(params) {

            showFade();

            var processOperators = {};
            var primaryLength = 0;

            $("div#resultToursSearch", $wrapper).show();
            $("div.t-resultProgress", $wrapper).css("width", "1%").html("");
            $("div.t-resultMain", $wrapper).html("");
            $("div.t-toursPager", $wrapper).html("");
            $(".t-resultAmount span.t-showResult", $wrapper).html("0 туров").show();

            var req = new XMLHttpRequest();
            var formData = new FormData();
            var lastDelimiter = 0;
            var delimiter;
            var data = [];
            var oid;

            delete tPager;
            tPager = null;

            req.onreadystatechange = function () {

                if (req.status == 200) {

                    if ((delimiter = req.responseText.substring(lastDelimiter).lastIndexOf("\n")) !== -1) {
                        var responseText = req.responseText.substring(lastDelimiter, lastDelimiter + delimiter).trim().split("\n");

                        for (var line = 0; line < responseText.length; line++) {

                            var tours = JSON.parse(responseText[line]);
                            oid = tours["oid"];
                            data = tours["t"] ? data.concat(tours["t"]) : data;

                            if (processOperators["operator_" + oid] !== true) {
                                // for more specific testing need additional if
                                processOperators["operator_" + oid] = tours["t"] ? true : tours["t"];
                            }

                            var percents = $.objectKeys(processOperators).length / allOperatorsCount * 100;
                            percents = percents - (percents % 1);
                            percents = percents >= 100 ? 95 : percents;

                            $("div.t-resultProgress div", $wrapper).css("width", percents + "%").html(percents + "%");

                        }

                        if (data.length) {

                            hideFade();

                            // Если Пейджера еще нет, а данные есть тогда инициализируем его, рендерим,
                            // А также убераем фейд и скроллим к первым полученным данным о турах
                            if( !tPager ) {
                                tPager = new Pager($.extend(settings, {data: data}));
                                primaryLength = data.length;
                                tPager.redraw();

                                $(".t-resultAmount span.t-showResult", $wrapper).show();

                                // Проскролить к результату загрузки туров после загрузки
                                scrollToResult();

                            } else {

                                // Обновляем данные Пейджера, если он уже есть и есть данные
                                tPager.setTours(data);
                            }

                            $(".t-resultAmount span.t-showResult", $wrapper).html(getToursCount(data.length));
                            $(".t-resultAmount .t-refreshResult", $wrapper).show();
                        }

                        lastDelimiter = lastDelimiter + delimiter + 1;
                    }

                    if (req.readyState == 4) {

                        hideFade();

                        if( req.responseText == "expired" || req.responseText == "403" ){
                            if( typeof settings["result_error_func"] == "function"){
                                settings["result_error_func"](req.responseText);
                            }
                            return false;
                        }

                        if (data.length <= primaryLength) {
                            $(".t-resultAmount .t-refreshResult", $wrapper).hide();
                        } else {
                            $(".t-resultAmount .t-refreshResult", $wrapper).show();
                        }

                        if (!tPager) {

                            hideFade();
                            tPager = new Pager($.extend(settings, {data: data}));
                            tPager.redraw();
                            scrollToResult();

                        } else if(data.length > primaryLength) {
                            tPager.setTours(data);
                        }

                        $("div.t-resultProgress", $wrapper).css("width", "100%").html("<span>Загрузка туров: 100%</span>");
                        scrollToResult();
                    }

                }

            };

            for (var property in params) {
                if ($.isArray(params[property])) {
                    for (var i = 0, l = params[property].length; i < l; ++i) {
                        formData.append("params[" + property + "][]", params[property][i]);
                }
                } else {
                    formData.append("params[" + property + "]", params[property]);
                }
            }

            req.open("POST", settings['result_url']);
            req.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            req.send(formData);

            return true;
        }


        var Pager = function (settings) {

            var sortSearchTours = function (tours) {
                tours.sort(function (t1, t2) {
                    if (parseInt(t1.tPrice) > parseInt(t2.tPrice)) return 1;
                    if (parseInt(t1.tPrice) < parseInt(t2.tPrice)) return -1;
                });

                return tours;
            }

            var initTable = function (headers, tbl_classes) {

                if (tbl_classes === undefined || !tbl_classes) {
                    tbl_classes = 'table table-unbordered table-hovered panel-table table-striped';
                }

                tbl_classes += ' t-table-result';

                $(".t-resultMain").html('<table class="' + tbl_classes + '"><tbody></tbody>');

                if (headers !== undefined) {
                    var th = [];
                    for (var i = 0, l = headers.length; i < l; ++i) {
                        th[i] = '<th>' + headers[i] + '</th>';
                    }

                    $(".t-resultMain table").prepend('<thead>' + th.join("") + '</thead>');
                }
            }

            var render = function (tours, size, page, row) {
                var htmlData = [];

                if (tours.length) {

                    if (page === undefined) {
                        page = 1;
                    }

                    var data = extractPageData(tours, page, size);

                    var k = 0;
                    var nr = (page - 1) * size;
                    for (var i = 0, l = data.length; i < l; ++i) {
                        htmlData[k++] = '<tr>' + row(data[i], nr + k) + '</tr>';
                    }
                }

                $("div.t-resultMain table tbody", $wrapper).get(0).innerHTML = htmlData.join("");
            }

            var extractPageData = function (tours, page, size) {
                var total = tours.length;
                var maxIndex = total - 1;
                var pages = calcPages(total, size);

                if (!pages) {
                    return [];
                }

                if (page > pages) {
                    page = pages
                }

                var rightIndex = page * size - 1;
                var leftIndex = rightIndex - size + 1;


                if (maxIndex < rightIndex) {
                    rightIndex = maxIndex;
                    leftIndex = (page - 1) * size;
                }

                var data = [];
                for (var index = leftIndex; index <= rightIndex; ++index) {
                    data[data.length] = tours[index];
                }

                return data;
            }

            var setToursAmount = function (amount) {
                $(".t-resultAmount span.t-showResult", $wrapper).html(getToursCount(amount));
            }

            var setPagination = function (total, size, page) {
                page = $.toInt(page);

                var pages = calcPages(total, size);
                var page = page ? page : 1;
                var hPages = [];

                // инициализация паджинации
                $("div.t-toursPager", $wrapper).html("");

                if (pages > 1) {


                    if (pages <= 6) {
                        for (var p = 1; p <= pages; ++p) {
                            hPages[hPages.length] = "<a href='#' page='" + p + "' " + ( p == page ? "class='active'" : "" ) + ">" + p + "</a>";

                        }

                    } else if (page <= 5) {

                        var p = 1;
                        do {
                            hPages[hPages.length] = "<a href='#' page='" + p + "' " + ( p == page ? "class='active'" : "" ) + ">" + p + "</a>";
                            ++p;
                        } while (page - p >= -1 || p <= 3);

                        if (pages - hPages.length > 3) {
                            hPages[hPages.length] = "<i>...</i>";
                            hPages[hPages.length] = "<a href='#' page='" + (pages - 1) + "' >" + (pages - 1) + "</a>";
                            hPages[hPages.length] = "<a href='#' page='" + pages + "' >" + pages + "</a>";
                        } else {
                            for (var p = hPages.length + 1; p <= pages; ++p) {
                                hPages[hPages.length] = "<a href='#' page='" + p + "' >" + p + "</a>";
                            }
                        }


                    } else if (page >= pages - 4) {

                        hPages[hPages.length] = "<a href='#' page='1' >1</a>";
                        hPages[hPages.length] = "<a href='#' page='2' >2</a>";
                        hPages[hPages.length] = "<i>...</i>";

                        var offset = pages == page ? 2 : 1;
                        for (var p = page - offset; p <= pages; ++p) {
                            hPages[hPages.length] = "<a href='#' page='" + p + "'  " + ( p == page ? "class='active'" : "" ) + ">" + p + "</a>";
                        }

                    } else {

                        hPages[hPages.length] = "<a href='#' page='1' >1</a>";
                        hPages[hPages.length] = "<a href='#' page='2' >2</a>";
                        hPages[hPages.length] = "<i>...</i>";

                        for (var p = page - 1; p <= page + 1; ++p) {
                            hPages[hPages.length] = "<a href='#' page='" + p + "'  " + ( p == page ? "class='active'" : "" ) + ">" + p + "</a>";
                        }

                        hPages[hPages.length] = "<i>...</i>";
                        hPages[hPages.length] = "<a href='#' page='" + (pages - 1) + "' >" + (pages - 1) + "</a>";
                        hPages[hPages.length] = "<a href='#' page='" + pages + "' >" + pages + "</a>";
                    }

                    $("div.t-toursPager", $wrapper).append(hPages.reverse().join(""));
                    $("div.t-toursPager", $wrapper).append("<span>Страница " + page + " из " + pages + "</span>");
                }

                return pages;
            }

            var calcPages = function (total, size) {
                var pages = 0;

                if (total) {
                    pages = Math.ceil(total / size);
                }

                return pages;
            }

            var calcToursAmount = function (data) {
                return data.length;
            }

            var setSize = function (size) {
                var allowedSizes = [10, 20, 50, 100, 200];
                if (size === undefined || $.inArray(parseInt(size), allowedSizes) == -1) {
                    size = 20;
                }

                // настройка размера страницы
                $("div.t-toursResultPager a[class='active']", $wrapper).removeClass("active");
                $("div.t-toursResultPager a[ps='" + size + "']", $wrapper).addClass("active");

                return size;
            }

            this.setPage = function (page) {
                page = $.toInt(page);

                if (page <= 0) {
                    page = 1;
                } else if (page > this.pages) {
                    page = this.pages;
                }

                render(this.tours, this.size, page, settings.row);
                setPagination(this.tours.length, this.size, page);
            }

            this.setSize = function (size) {
                this.size = setSize(size);
                render(this.tours, this.size, 1, settings.row);
                this.pages = setPagination(this.tours.length, this.size);
                scrollToResult();

            }

            this.setTours = function (tours) {

                this.tours = sortSearchTours(tours);

                // настройка паджинации
                this.pages = calcPages(this.tours.length, this.size);
            }

            this.redraw = function () {
                // инициализации размерности страниц
                this.size = setSize(this.size);

                // настройка паджинации
                this.pages = setPagination(this.tours.length, this.size);

                // количество туров
                this.tourAmount = calcToursAmount(this.tours);

                // настройка счетчика туров
                setToursAmount(this.tourAmount);

                // прорисовать
                render(this.tours, this.size, 1, settings.row);
            }

            // туры
            this.setTours(settings.data);

            this.size = settings.size;

            initTable(settings.headers, settings.tbl_classes);
        }


        $(function () {

            /****************************** "REFRESH SEARCH" ACTION ******************************/
            $("body").on("click", jWrapId + " a.t-refreshResult", function () {
                tPager.redraw();
                $(document.body).trigger("click");
                $(this).hide();

                return false;
            });

            /****************************** CHANGE "PAGE SIZE" ACTION ******************************/
            $("body").on("click", jWrapId + " div.t-toursResultPager a", function () {
                if (null !== tPager && !$(this).hasClass("active")) {
                    tPager.setSize($(this).attr("ps"));
                    $("a.t-refreshResult", $wrapper).hide();
                }

                return false;
            });

            /****************************** CHANGE "PAGE" ACTION ******************************/
            $("body").on("click", jWrapId + " div.t-toursPager a", function () {

                if (null !== tPager && !$(this).hasClass("active")) {
                    tPager.setPage($(this).text());
                    $("a.t-refreshResult", $wrapper).hide();
                }

                return false;
            });


        });


    }

})(jQuery);