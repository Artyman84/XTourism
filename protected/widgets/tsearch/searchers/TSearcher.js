/**
 * Created with JetBrains PhpStorm.
 * User: Arti
 * Date: 07.07.14
 * Time: 18:57
 * To change this template use File | Settings | File Templates.
 */

;(function($, undefined){

    const R_CHILDREN_INSTEAD_PARENT = 1;
    const R_CHILDREN_WITH_PARENT = 2;

    var $wrapper;
    var jWrapId;
    var iframeId;
    var userId;
    var tPager = null;
    var searchTimer = null;
    var hotelsData = [];


    /***************************** GETTERS ********************************/
    var getWindowHeight = function(){
        return $(document.body).outerHeight(true) + "px";
    }

    var sendMessageToParent = function(data) {
        data.iid = iframeId;
        parent.postMessage( JSON.stringify(data), "*");
    }

    var getCountry = function(){
        return $("select#country", $wrapper).val();
    };

    var getDepCity = function(){
        return $("select#depCity", $wrapper).val();
    };

    var getHotelCategories = function(){
        var categories = [String($("select#hotelCategory", $wrapper).val())];

        if( isHotelCategoryMore() ){
            $("select#hotelCategory option:selected", $wrapper).nextAll().each(function(i){
                categories[categories.length] = String($(this).val());
            });
        }

        return categories;
    }

    var isHotelCategoryMore = function(){
        return $.toInt($("input#hotelCategoryMore", $wrapper).is(":checked"));
    }

    var getMeals = function(){
        var meals = [String($("select#meals", $wrapper).val())];

        if( isMealsMore() ){
            $("select#meals option:selected", $wrapper).nextAll().each(function(i){
                meals[meals.length] = String($(this).val());
            });
        }

        return meals;
    }

    var isMealsMore = function(){
        return $.toInt($("input#mealsMore", $wrapper).is(":checked"));
    }

    var getResorts = function(r_children){

        var elements = document.querySelectorAll(jWrapId + " ul.t-resortsList li.active"),
            resorts  = [],
            children = [];

        for(var i=0,l=elements.length; i<l; ++i){

            // Если есть дочерние курорты и надо выбрать их вместо родительских комбинированных
            if( r_children !== undefined && r_children && elements[i].getAttribute("children") ){

                children = elements[i].getAttribute("children").split(',');

                for( var j=0, l1=children.length; j<l1; ++j ){
                    resorts.push(String(children[j]));
                }

                // Если надо выбрать родителя вместе с дочерними курортами
                if( r_children == R_CHILDREN_WITH_PARENT ) {
                    resorts.push(String(elements[i].value));
                }

            } else {

                // Если нет дочерних курортов или их не надо выбирать
                resorts.push(String(elements[i].value));
            }
        }

        return resorts;
    };

    var getHotels = function(){
        return $(jWrapId + " ul.t-hotelsList").data('selected') || [];
    };

    var getOperators = function(){

        var elements = document.querySelectorAll(jWrapId + " ul.t-operatorsList li.active"),
            operators = [];

        for(var i= 0,l=elements.length; i<l; ++i){
            operators[i] = String(elements[i].value);
        }

        return operators;

    };

    var getSearchOperators = function(){
        var operators = getOperators();

        if( !operators.length ){
            var elements = document.querySelectorAll(jWrapId + " ul.t-operatorsList li");
            for(var i= 0,l=elements.length; i<l; ++i){
                operators[i] = String(elements[i].value);
            }
        }

        return operators;
    }

    var getSearchText = function(){
        return $("input#searchHotelText", $wrapper).val();
    }

    var getNightFrom = function(){
        return $("select#nightFrom", $wrapper).val();
    };

    var getNightTo = function(){
        return $("select#nightTo", $wrapper).val();
    };

    var getAvailableDateFrom = function(){
        return $("input#availableDateFrom", $wrapper).val();
    };

    var getAvailableDateTo = function(){
        return $("input#availableDateTo", $wrapper).val();
    };

    var getAdults = function(){
        return $("input[name='adults']:checked", $wrapper).val();
    };

    var getChildren = function(){
        var maxCh = $.toInt(($("input[name='children']:checked", $wrapper).val()));
        var children = [];

        for(var ch = 1; ch <= maxCh; ch++){
            children[children.length] = $("select#child" + ch, $wrapper).val();
        }

        return children;
    };

    var getMinPrice = function(){
        return $("input#minPrice", $wrapper).val();
    }

    var getMaxPrice = function(){
        return $("input#maxPrice", $wrapper).val();
    }

    var getCurrency = function(){
        return $.toInt($("input[name='currency']:checked", $wrapper).val());
    }

    var getFli = function(){
        return $.toInt($("input#fli", $wrapper).is(":checked"));
    }

    var getPh = function(){
        return $.toInt($("input#ph", $wrapper).is(":checked"));
    }

    var getTourParams = function(){
        var params = {
            "operators": getSearchOperators(),
            "dirDepCity": getDepCity(),
            "dirCountry": getCountry(),
            "dirResort": getResorts(),
            "dirHotels": getHotels(),
            "dirHotelCategory": getHotelCategories(),
            "hCMore": isHotelCategoryMore(),
            "dirMeals": getMeals(),
            "mealMore": isMealsMore(),
            "availableDateFrom": getAvailableDateFrom(),
            "availableDateTo": getAvailableDateTo(),
            "nightFrom": getNightFrom(),
            "nightTo": getNightTo(),
            "adults": getAdults(),
            "children": getChildren(),
            "minPrice": getMinPrice(),
            "maxPrice": getMaxPrice(),
            "currency": getCurrency(),
            "flightNonstop": getFli(),
            "hotelNonstop": getPh()
        };

        return params;
    }


    /***************************** AUXILIARIES ********************************/

    var showFade = function(delay){
        sendMessageToParent({
            action: 'showFade',
            cc: "xtourism-" + $wrapper.attr("design-color") + " xtourism-spinner-load" + $wrapper.attr("spinner")
        });
    }

    var hideFade = function(){
        sendMessageToParent({action: 'hideFade'});
    }

    var showLoadingFade = function (identifier) {

        var fade_class = "loading-fade-" + identifier;

        if( !$.toInt($("body div." + fade_class).length) ){
            $("body").append('<div class="' + fade_class + ' xtourism-ajax-loader xtourism-' + $wrapper.attr("design-color") + ' xtourism-spinner-load' + $wrapper.attr("spinner") + '"><div class="xtourism-mini-spinner xtourism-spinner" ></div></div>');
        }

        if( !$( "body div." + fade_class).is(":visible") ) {
            $("body div." + fade_class).show();
        }

        resizeFades();
    }

    var hideLoadingFade = function (identifier) {

        var fade_class = "loading-fade-" + identifier;

        if( $.toInt($("body ." + fade_class).length) ){
            $( "body ." + fade_class).fadeOut("hide", function(){$(this).remove();});
        }
    }

    var resizeFades = function(){
        if( $("[class^='loading-fade-']").length ) {
            var elements = {"r": ".t-resorts-group", "h": ".t-hotels-group", "o": ".t-operators-group"};
            for (var id in elements) {
                var $el = $(elements[id], $wrapper);
                $(".loading-fade-" + id).css({
                    top: $el.offset().top,
                    left: $el.offset().left,
                    width: $el.innerWidth(),
                    height: $el.innerHeight()
                });
            }
        }
    }

    var showLoadingRHO = function(){
        showLoadingFade("r");
        showLoadingFade("h");
        showLoadingFade("o");
    }

    var disableWidgetsFWC = function(){
        $("select#depCity, select#country, select#hotelCategory, input#hotelCategoryMore", $wrapper).prop("disabled", true);
    }

    var enableWidgetsFWC = function(){
        $("select#depCity, select#country, select#hotelCategory, input#hotelCategoryMore", $wrapper).prop("disabled", false);
    }

    var resetHotelsScroll = function(){
        $("div.t-modHotels ul.t-hotelsList", $wrapper).parent().scrollTop(0);
    }

    var updatingWidget = {

        countries: function(countries){
            var options = [],
                country = getCountry();

            for( var i= 0,l=countries.length; i<l; ++i ){

                var selected = "";
                if( countries[i]["id"] == country ){
                    selected = "selected='selected'";
                }

                options = options.concat(["<option value='", countries[i]["id"], "' ", selected, ">", $.escapeHtml(countries[i]["name"]), "</option>"]);
            }

            $("div.t-modCountries select#country", $wrapper).get(0).innerHTML = options.join("");
        },

        resorts: function(data){

            var options = [],
                children = {},
                resorts = getResorts(),
                populateOptions = function(options, element, resorts){
                    var attr = "";

                    if (resorts.indexOf(element["id"]) != -1){
                        attr = 'class="active"';
                    }

                    if( element["children"] !== undefined ) {
                        attr += ' children = "' + element["children"].join(",") + '"';
                    }

                    if( parseInt(element["parent_id"]) ) {
                        attr += ' style="margin-left: 20px" parent_id="' + element["parent_id"] + '"';
                    }

                    options = options.concat(["<li value='", element["id"], "' ", attr, "><i></i>", $.escapeHtml(element["name"]), "</li>"]);

                    return options;
                };


            // Собираем дочерние курорты в группы
            for (var i= 0,l=data.length; i<l; ++i) {
                var parent_id = data[i]["parent_id"];

                // Дети
                if( parseInt(parent_id) ) {
                    if( children["_" + parent_id] === undefined ) {
                        children["_" + parent_id] = [];
                    }

                    children["_" + parent_id].push(data[i]);
                }
            }

            for (var i=0,l=data.length; i<l; ++i) {

                // Если курорт является дочерникм - выводим его ниже под своим родителем.
                if( parseInt(data[i]["parent_id"]) ) continue;

                options = populateOptions(options, data[i], resorts);

                // Выводим группу дочерних курортов для комбинированного курорта
                if( parseInt(data[i]["is_combined"]) && children["_" + data[i]["id"]] !== undefined ) {
                    for( var j=0, l1=children["_" + data[i]["id"]].length; j<l1; ++j ){
                        options = populateOptions(options, children["_" + data[i]["id"]][j], resorts);
                    }
                }
            }

            $("div.t-modResorts ul.t-resortsList", $wrapper).get(0).innerHTML = options.join("");
            hideLoadingFade("r");
        },

        hotels: function(index_id){

            var hotel, li, checked,
                onlySelected = !!$(jWrapId + " a#selectedHotels.non-active:not(.non-access)").length,
                resorts = getResorts(R_CHILDREN_INSTEAD_PARENT),
                hotels = getHotels(),
                categories = getHotelCategories(),
                operators = getOperators();

            var limit = 150;
            var fragment = document.createDocumentFragment();
            var searchText = getSearchText();
            var matcher = searchText? new RegExp(searchText.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&"), "i"): null;

            for( var i = index_id || 0, l=hotelsData.length; (i<l) && limit; ++i ){
                hotel = hotelsData[i];
                checked = hotels.indexOf(hotel['id']) !== -1;
                if (
                    checked ||
                    (
                        (checked || !onlySelected) &&
                        (!matcher || matcher.test(hotel['name'])) &&
                        categories.indexOf(hotel['category_id']) !== -1 &&
                        (!resorts.length || resorts.indexOf(hotel['resort_id']) !== -1) &&
                        (!operators.length || operators.some(function(currentValue){ return hotel["operators"].indexOf("," + currentValue + ",") !== -1; }))
                    )
                ) {

                    li = document.createElement('li');
                    li.value = hotel['id'];
                    li.className = checked? "active": "";
                    li.innerHTML = '<i></i>' + $.escapeHtml(hotel["name"]);
                    fragment.appendChild(li);
                    limit--;

                }
            }

            var dropDown = $("div.t-modHotels ul.t-hotelsList", $wrapper).get(0);

            if (!index_id) {
                while (dropDown.firstElementChild) {
                    dropDown.removeChild(dropDown.firstElementChild);
                }
            }

            dropDown.appendChild(fragment);
            $(dropDown).data('index_id', i == l? -1: i);

            hideLoadingFade("h");
        },

        operators: function(operators){
            var options = [], checked,
                selected = getOperators();

            for( var i= 0,l=operators.length; i<l; ++i ){
                checked = "";

                if (selected.indexOf(operators[i]["id"]) != -1){
                    checked = "class='active'";
                }

                options = options.concat(["<li value='", operators[i]["id"], "' ", checked, "><i></i>", $.escapeHtml(operators[i]["name"]), "</li>"]);
            }

            $("div.t-modOperators ul.t-operatorsList", $wrapper).get(0).innerHTML = options.join("");
            hideLoadingFade("o");
        }
    }

    var updateDepCities = function(){
        showLoadingRHO();
        disableWidgetsFWC();

        $.sendRequest(
            "FrontSearcher/changeDepCity/" + userId,
            {
                "dirDepCity": getDepCity(),
                "dirCountry": getCountry()
            },
            function(data){

                if( (data = validateResponse(data)) !== false ) {
                    hotelsData = data['h'];
                    updatingWidget["operators"](data["o"]);
                    updatingWidget["countries"](data["c"]);
                    updatingWidget["resorts"](data["r"]);

                    resetHotelsScroll();
                    updatingWidget["hotels"]();

                    enableWidgetsFWC();
                }

            },
            "HTML",
            false
        );
    }

    var updateCountries = function(){
        showLoadingRHO();
        disableWidgetsFWC();

        $.sendRequest(
            "FrontSearcher/changeCountry/" + userId,
            {
                "dirDepCity": getDepCity(),
                "dirCountry": getCountry()
            },
            function(data){

                if( (data = validateResponse(data)) !== false ) {

                    $('ul.t-hotelsList', $wrapper).data('selected', []);

                    hotelsData = data["h"];
                    updatingWidget["operators"](data["o"]);
                    updatingWidget["resorts"](data["r"]);

                    resetHotelsScroll();
                    updatingWidget["hotels"]();

                    enableWidgetsFWC();
                }
            },
            "HTML",
            false
        );
    }

    var getToursCount = function(count){
        switch ($.endingNumberType(count)){
            case 1: return count + " " + "тур";
            case 2: return count + " " + "тура";
            case 3: return count + " " + "туров";
        }
    }

    var validateResponse = function(response){

        if( response == "expired" || response == ""){
            expiredAlert();
            return false;
        }

        return JSON.parse(response);
    }

    var expiredAlert = function(){
        $(document.body).html('<div class="alert alert-warning text-center"><span class="glyphicon glyphicon-warning-sign"></span> Поиск туров временно недоступен</div>')
        $(document.body).trigger("click");
    }

    var scrollToResult = function(){
        var topMargin = 15;
        sendMessageToParent({
            action: 'scrollTo',
            top: $("#xtourism-results").offset().top + $(window.document.body).offset().top - topMargin
        });
    }

    var runSearch = function(){

        showFade();

        var params = getTourParams();
        var processOperators = {}, allOperatorsCount = params["operators"].length, primaryLength=0;
        var size = tPager ? tPager.size : 20;
        var mode = tPager ? tPager.mode : 0;

        $("div#xtourism-results", $wrapper).show();
        $("div.t-resultProgress div", $wrapper).css("width", "0%").html("");
        $(".t-resultAmount span.t-showResult", $wrapper).html("0");
        $("div.t-resultMain table:first tbody:first, div.t-toursPager", $wrapper).html("");

        var req = new XMLHttpRequest();
        var formData = new FormData();
        var lastDelimiter = 0;
        var delimiter;
        var data = [];
        var oid;

        delete tPager;
        tPager = null;

        req.onreadystatechange = function() {

            if( req.status == 200 ){

                if ((delimiter = req.responseText.substring(lastDelimiter).lastIndexOf("\n")) !== -1) {
                    var responseText = req.responseText.substring(lastDelimiter, lastDelimiter + delimiter).trim().split("\n");

                    for (var line = 0; line < responseText.length; line++) {

                        var tours = JSON.parse(responseText[line]);
                        oid = tours["oid"];
                        data = tours["t"] ? data.concat(tours["t"]) : data;

                        if( processOperators["operator_" + oid] !== true ){
                            // for more specific testing need additional if
                            processOperators["operator_" + oid] = tours["t"] ? true : tours["t"];
                        }

                        var percents = $.objectKeys(processOperators).length/allOperatorsCount*100;
                        percents = percents - (percents%1);
                        percents = percents >= 100 ? 95 : percents;

                        $("div.t-resultProgress div", $wrapper).css("width", percents + "%").html(percents + "%");

                    }

                    // Пришли данные
                    if( data.length ) {

                        hideFade();

                        // Если Пейджера еще нет, а данные есть тогда инициализируем его, рендерим,
                        // А также убераем фейд и скроллим к первым полученным данным о турах
                        if( !tPager ) {

                            tPager = new Pager(data, size, mode);
                            primaryLength = data.length;
                            tPager.redraw();

                            $(".t-resultAmount span.t-showResult", $wrapper).show();

                            // Проскролить к результату загрузки туров после загрузки
                            scrollToResult();

                            // Для перерисовки высоты iframe, если вдруг высота списка отелей будет больше первоначальной высоты
                            $(document.body).trigger("click");

                        } else {

                            // Обновляем данные Пейджера, если он уже есть и есть данные
                            tPager.setTours(data);
                        }

                        $(".t-resultAmount span.t-showResult", $wrapper).html( getToursCount(data.length) );
                        $(".t-resultAmount .t-refreshResult", $wrapper).show();
                    }

                    lastDelimiter = lastDelimiter + delimiter + 1;
                }

                if( req.readyState == 4 ){

                    if( req.responseText == "expired" || req.responseText == "403" ){
                        expiredAlert();
                        return false;
                    }

                    if( data.length <= primaryLength ){
                        $(".t-resultAmount .t-refreshResult", $wrapper).hide();
                    } else {
                        $(".t-resultAmount .t-refreshResult", $wrapper).show();
                    }

                    if(!tPager) {

                        hideFade();
                        tPager = new Pager(data, size, mode);
                        tPager.redraw();
                        scrollToResult();

                    } else {
                        tPager.setTours(data);
                    }

                    $("div.t-resultProgress div", $wrapper).css("width", "100%").html("100%");
                }

            }

        };

        for( var property in params ){
            if( $.isArray(params[property]) ){
                for(var i= 0,l=params[property].length; i<l; ++i) {
                    formData.append("params[" + property + "][]", params[property][i]);
                }
            } else {
                formData.append("params[" + property + "]", params[property]);
            }
        }

        req.open( "POST", $.createUrl("FrontSearcher/search/" + userId) );
        req.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        req.send(formData);

        return true;
    }

    var Pager = function(data, size, mode){

        var sortSearchTours = function(tours){
            tours.sort(function(t1, t2){
                if( parseInt(t1.tPrice) > parseInt(t2.tPrice) ) return 1;
                if( parseInt(t1.tPrice) < parseInt(t2.tPrice) ) return -1;
            });

            return tours;
        }

        var htmlByHotel = function(data){

            var h = [];
            for(var i=0,l1=data.length; i<l1; ++i){

                var length = data[i].length;
                var first = data[i][0];
                var last = data[i][length-1];
                var lengthText = "показать " + getToursCount(length);

                h = h.concat(
                    ['<tr><td><img width="165" height="70" src="', first.hImgPath, '" alt=""></td>'],
                    ['<td><h4><a class="cap ib" target="_blank" href="', $.createUrl("Hotel/hotelInfo", {"uid" : userId, "hId": first.hHashId}) ,'">', first.hName, ' ', first.hCategory ,'</a>&nbsp;</h4><h4><span class="cap ib">', first.hResortPath, '</span></h4></td>'],
                    ['<td class="rating">рейтинг: <em>', (first.hasOwnProperty("hRating") ? first.hRating : "") ,'</em><div class="tip"><div class="tip-wrap"><div class="tip-text">', first.hScores ,'</div></div></div><span>', first.hVoices ,'</span>&nbsp;отзывов</td>'],
                    ['<td><div class="price"><span>', first.tNormalizedPrice ,'</span>&nbsp;—&nbsp;<span>', last.tNormalizedPrice ,'</span>&nbsp;', last.tHtmlCurrency ,'</div><div class="show-all"><a href="#" class="t-showTours">', lengthText ,'</a></div></td>'],
                    ['</tr><tr><td colspan="4"><table><tbody><tr><th>даты</th><th>питание</th><th>оператор</th><th>размещение</th><th colspan="5">доступность</th><th>стоимость</th><th>&nbsp;</th></tr>']
                );

                for(var j= 0, l2=length; j<l2; ++j){
                    var tour = data[i][j];

                    h = h.concat(
                        ['<tr><td><span><span>', tour.tStartResDateDM ,'<i>', tour.tNightsTxt ,'</i></span><div class="tip"><div class="tip-wrap"><div class="tip-text">&nbsp;&nbsp;&nbsp;&nbsp;', tour.tStartWeekDay ,' - ', tour.tEndWeekDay, '<br>&nbsp;&nbsp;&nbsp;&nbsp;', tour.tDaysTxt ,', ', tour.tNightsTxt, '</div></div></div></span></td>'],
                        ['<td class="resfood"><span>', tour.tMeal ,'</span><div class="tip"><div class="tip-wrap"><div class="tip-text">', tour.tMealDescription ,'</div></div></div></td>'],
                        ['<td><span><img src="', tour.oImgPath ,'" height="25" width="30" border="0/"></span><div class="tip"><div class="tip-wrap"><div class="tip-text">', tour.oName ,'</div></div></div></td>'],
                        ['<td class="cap"><span>', tour.hResidence ,'</span><div class="tip"><div class="tip-wrap"><div class="tip-text">', tour.tRoom ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssStatus ,'"><i class="fa fa-home"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Места в отеле: ', tour.hStatusDescription ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssETicketTo ,'"><i class="fa fa-plane"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Билеты туда (эконом класс): ', tour.hETicketDescriptionTo ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssETicketFrom ,'"><i class="fa fa-plane fa-rotate-180"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Билеты обратно (эконом класс): ', tour.hETicketDescriptionFrom ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssBTicketTo ,'"><i class="fa fa-plane"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Билеты туда (бизнес класс): ', tour.hBTicketDescriptionTo ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssBTicketTo ,'"><i class="fa fa-plane fa-rotate-180"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Билеты обратно (бизнес класс): ', tour.hBTicketDescriptionFrom ,'</div></div></div></td>'],
                        ['<td><div class="price"><span>', tour.tNormalizedPrice ,'</span>&nbsp;', tour.tHtmlCurrency ,'</i></div></td>'],
                        ['<td><div class="button" style="width: 82px;"><a target="_blank" class="text" href="', $.createUrl("FrontSearcher/tourRequest", {"id": userId, "p":tour.tRequestParams}) ,'">Заказ</a></div></td></tr>']
                    );
                }

                h = h.concat(['</tbody></table></td></tr>']);
            }

            return h.join('');
        }

        var htmlByTours = function(data){
            var h = ['<tr><td colspan="4" class="bigger"><table><tbody><tr><th collspan="11">отель, курорт</th></tr><tr><th>даты</th><th>питание</th><th>оператор</th><th>размещение</th><th colspan="5">доступность</th><th>стоимость</th><th>&nbsp;</th></tr>'];
            for(var i=0,l1=data.length; i<l1; ++i){
                var tour = data[i];

                h = h.concat(
                    ['<tr><td class="hinfo" colspan="11"><a class="cap ib" target="_blank" href="', $.createUrl("Hotel/hotelInfo", {"uid" : userId, "hId": tour.hHashId}) ,'">', tour.hName ,'&nbsp;', tour.hCategory ,'</a>&nbsp;&nbsp;<span>', tour.hResortPath ,'</span></td></tr>'],
                    ['<tr class="unbord"><td><span><span>', tour.tStartResDateDM ,'<i>', tour.tNightsTxt ,'</i></span><div class="tip"><div class="tip-wrap"><div class="tip-text">&nbsp;&nbsp;&nbsp;&nbsp;', tour.tStartWeekDay ,' - ', tour.tEndWeekDay, '<br>&nbsp;&nbsp;&nbsp;&nbsp;', tour.tDaysTxt ,', ', tour.tNightsTxt ,'</div></div></div></span></td><td class="resfood"><span>', tour.tMeal ,'</span><div class="tip"><div class="tip-wrap"><div class="tip-text">', tour.tMealDescription ,'</div></div></div></td><td><span><img src="', tour.oImgPath ,'" height="25" width="30" border="0/"></span><div class="tip"><div class="tip-wrap"><div class="tip-text">', tour.oName ,'</div></div></div></td><td><span>', tour.hResidence ,'</span><div class="tip"><div class="tip-wrap"><div class="tip-text">', tour.tRoom ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssStatus ,'"><i class="fa fa-home"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Места в отеле: ', tour.hStatusDescription ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssETicketTo ,'"><i class="fa fa-plane"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Билеты туда (эконом класс): ', tour.hETicketDescriptionTo ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssETicketFrom ,'"><i class="fa fa-plane fa-rotate-180"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Билеты обратно (эконом класс): ', tour.hETicketDescriptionFrom ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssBTicketTo ,'"><i class="fa fa-plane"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Билеты туда (бизнес класс): ', tour.hBTicketDescriptionTo ,'</div></div></div></td>'],
                        ['<td class="icon ', tour.hCssBTicketFrom ,'"><i class="fa fa-plane fa-rotate-180"></i><div class="tip"><div class="tip-wrap"><div class="tip-text">Билеты обратно (бизнес класс): ', tour.hBTicketDescriptionFrom ,'</div></div></div></td>'],
                        ['<td><div class="price"><span>', tour.tNormalizedPrice ,'</span>&nbsp;', tour.tHtmlCurrency ,'</i></div></td><td><div class="button" style="width: 82px;"><a target="_blank" class="text" href="', $.createUrl("FrontSearcher/tourRequest", {"id": userId, "p":tour.tRequestParams}) ,'">Заказ</a></div></td></tr>']
                );
            }

            h = h.concat(['</tbody></table></td></tr>']);
            return h.join('');
        }

        var render = function(tours, mode, size, page){
            //showFade(1);
            var htmlData = '';

            if( tours.length ) {

                if (page === undefined) {
                    page = 1;
                }

                var data = extractPageData(tours, page, size);

                if (mode) {
                    htmlData = htmlByTours(data);
                } else {
                    htmlData = htmlByHotel(data);
                }
            }

            $("div.t-resultMain table:first tbody:first", $wrapper).get(0).innerHTML = htmlData;
            //hideFade();
        }

        var extractPageData = function(tours, page, size){
            var total = tours.length;
            var maxIndex = total - 1;
            var pages = calcPages(total, size);

            if( !pages ){
                return [];
            }

            if( page > pages ){
                page = pages
            }

            var rightIndex = page*size - 1;
            var leftIndex = rightIndex - size + 1;


            if( maxIndex < rightIndex ){
                rightIndex = maxIndex;
                leftIndex = (page - 1)*size;
            }

            var data = [];
            for( var index=leftIndex; index<=rightIndex; ++index ){
                data[data.length] = tours[index];
            }

            return data;
        }

        var groupByTours = function(data){
            var hLength = data.length;
            var tTours = [];

            for(var i=0; i<hLength; ++i){
                var tLength = data[i].length;

                for( var j=0; j<tLength; ++j){

                    var tHotel = {};
                    for( var key in data[i][j] ){
                        tHotel[key] = data[i][j][key];
                    }

                    tTours[tTours.length] = tHotel;
                }
            }

            return sortSearchTours(tTours);
        }

        var groupByHotels = function(data){
            var hLength = data.length;
            var tTours = {};

            for( var i=0; i<hLength; ++i ){
                var key = "t_" + data[i].hId;

                if( !tTours.hasOwnProperty(key) ){
                    tTours[key] = [];
                }
                tTours[key][tTours[key].length] = data[i];
            }

            tTours = $.arrayValues(tTours);

            return tTours;
        }

        var setToursAmount = function(amount){
            $(".t-resultAmount span.t-showResult", $wrapper).html( getToursCount(amount) );
        }

        var setPagination = function(total, size, page){
            page = $.toInt(page);

            var pages = calcPages(total, size);
            var page = page ? page : 1;
            var hPages = [];

            // инициализация паджинации
            $("div.t-toursPager", $wrapper).html("");

            if( pages > 1 ){


                if( pages <= 6 ){
                    for( var p=1; p<=pages; ++p ){
                        hPages[hPages.length] = "<a href='#' page='" + p + "' " + ( p == page ? "class='active'" : "" ) + ">" + p + "</a>";

                    }

                } else if( page <= 5 ) {

                    var p = 1;
                    do {
                        hPages[hPages.length] = "<a href='#' page='" + p + "' " + ( p == page ? "class='active'" : "" ) + ">" + p + "</a>";
                        ++p;
                    } while(page - p >= -1 || p <= 3);

                    if( pages - hPages.length > 3 ){
                        hPages[hPages.length] = "<i>...</i>";
                        hPages[hPages.length] = "<a href='#' page='" + (pages - 1) + "' >" + (pages - 1) + "</a>";
                        hPages[hPages.length] = "<a href='#' page='" + pages + "' >" + pages + "</a>";
                    } else {
                        for( var p = hPages.length + 1; p <= pages; ++p ){
                            hPages[hPages.length] = "<a href='#' page='" + p + "' >" + p + "</a>";
                        }
                    }


                } else if( page >= pages - 4 ){

                    hPages[hPages.length] = "<a href='#' page='1' >1</a>";
                    hPages[hPages.length] = "<a href='#' page='2' >2</a>";
                    hPages[hPages.length] = "<i>...</i>";

                    var offset = pages == page ? 2 : 1;
                    for( var p = page - offset; p <= pages; ++p ){
                        hPages[hPages.length] = "<a href='#' page='" + p + "'  " + ( p == page ? "class='active'" : "" ) + ">" + p + "</a>";
                    }

                } else {

                    hPages[hPages.length] = "<a href='#' page='1' >1</a>";
                    hPages[hPages.length] = "<a href='#' page='2' >2</a>";
                    hPages[hPages.length] = "<i>...</i>";

                    for( var p = page - 1; p <= page + 1; ++p ){
                        hPages[hPages.length] = "<a href='#' page='" + p + "'  " + ( p == page ? "class='active'" : "" ) + ">" + p + "</a>";
                    }

                    hPages[hPages.length] = "<i>...</i>";
                    hPages[hPages.length] = "<a href='#' page='" + (pages - 1) + "' >" + (pages - 1) + "</a>";
                    hPages[hPages.length] = "<a href='#' page='" + pages + "' >" + pages + "</a>";
                }

                $("div.t-toursPager", $wrapper).append(hPages.reverse().join(""));
                $("div.t-toursPager", $wrapper).append( "<span>Страница " + page + " из " + pages + "</span>" );
            }

            return pages;
        }

        var calcPages = function(total, size){
            var pages = 0;

            if( total ){
                pages = Math.ceil( total/size );
            }

            return pages;
        }

        var calcToursAmount = function(data, mode){
            if( mode ){
                return data.length;
            } else {
                var length = data.length;
                var amount = 0;

                for( var i=0; i<length; ++i ){
                    amount += data[i].length;
                }

                return amount;
            }
        }

        var setSize = function(size){
            var allowedSizes = [10, 20, 50, 100, 200];
            if( size === undefined || $.inArray(parseInt(size), allowedSizes) == -1 ){
                size = 20;
            }

            // настройка размера страницы
            $("div.t-toursResultPager a[class='active']", $wrapper).removeClass("active");
            $("div.t-toursResultPager a[ps='" + size + "']", $wrapper).addClass("active");

            return size;
        }

        var setMode = function(mode){
            $("div.t-viewMode input#viewtype" + mode, $wrapper).attr("checked", true);
        }


        this.setPage = function(page){
            page = $.toInt(page);

            if( page <= 0 ){
                page = 1;
            } else if( page > this.pages ){
                page = this.pages;
            }

            render(this.tours, this.mode, this.size, page);
            setPagination(this.tours.length, this.size, page);
        }

        this.setSize = function(size){
            this.size = setSize(size);
            render(this.tours, this.mode, this.size);
            this.pages = setPagination(this.tours.length, this.size);
        }

        this.setMode = function(mode){
            mode = $.toInt(mode);

            if( this.mode != mode ) {
                if( mode ){
                    this.tours = groupByTours(this.tours);
                } else {
                    this.tours = groupByHotels(this.tours);
                }

                this.mode = mode;
                this.pages = setPagination(this.tours.length, this.size);

                render(this.tours, this.mode, this.size);
            }

        }

        this.setTours = function(tours){
            this.tours = sortSearchTours(tours);
            if( !this.mode ){
                this.tours = groupByHotels(this.tours);
            }

            // настройка паджинации
            this.pages = calcPages(this.tours.length, this.size);
        }

        this.init = function(data, size, mode){

            // режим отображения туров
            this.mode = $.toInt(mode);

            // туры
            this.setTours(data);

            // инициализации размерности страниц
            this.size = setSize(size);

            // количество туров
            this.tourAmount = calcToursAmount(this.tours, this.mode);

            // настройка паджинации
            this.pages = setPagination(this.tours.length, this.size);

            // настройка счетчика туров
            setToursAmount(this.tourAmount);

            // настройка типа отображения
            setMode(this.mode);

            // выводим туры сразу после инициализации данных
            render(this.tours, this.mode, this.size);
        }

        this.redraw = function(){

            // настройка типа отображения
            setMode(this.mode);

            // инициализации размерности страниц
            this.size = setSize(this.size);

            // настройка паджинации
            this.pages = setPagination(this.tours.length, this.size);

            // количество туров
            this.tourAmount = calcToursAmount(this.tours, this.mode);

            // настройка счетчика туров
            setToursAmount(this.tourAmount);

            // прорисовать
            render(this.tours, this.mode, this.size);
        }


        // режим отображения туров
        this.mode = $.toInt(mode);

        // туры
        this.setTours(data);

        this.size = size;

    }

    window.TSearcher = function(_wrapperId, iframe_id, user_id){

        jWrapId = "div#" + _wrapperId;
        $wrapper = $("#" + _wrapperId);
        iframeId = iframe_id;
        userId = user_id;

        $(function(){

            $(window).resize(function(e){
                resizeFades();
                sendMessageToParent({h: getWindowHeight()});
            });

            document.body.addEventListener("click", function(){
                setTimeout(function(){sendMessageToParent({h: getWindowHeight()});}, 0);
            }, false);

            /****************************** CHANGE "DEPARTURE CITY" ACTION ******************************/
            $("body").on("change", jWrapId + " select#depCity", function(){
                updateDepCities();
            });

            /****************************** CHANGE "COUNTRY" ACTION ******************************/
            $("body").on("change", jWrapId + " select#country", function(){
                updateCountries();
            });

            /****************************** CHANGE "HOTEL CATEGORY" ACTION ******************************/
            $("body").on("change", jWrapId + " select#hotelCategory", function(){
                //showLoadingFade("h");
                resetHotelsScroll();
                updatingWidget["hotels"]();
            });

            /****************************** CHANGE "HOTEL CATEGORY MORE" ACTION ******************************/
            $("body").on("change", jWrapId + " input#hotelCategoryMore", function(){
                $("select#hotelCategory", $wrapper).trigger("change");
            });

            /****************************** CHANGE "RESORTS" ACTION ******************************/
            $("body").on("click", jWrapId + " ul.t-resortsList li", function(){
                //showLoadingFade("h");
                $(this).toggleClass("active");

                // Был нажат комбинированный курорт? Обрабатываем дочерние курорты.
                if( $(this).attr("children") ) {

                    var children = $(this).attr("children").split(',');
                    var is_active = $(this).hasClass("active");

                    for( var i=0, l=children.length; i<l; ++i ){
                        if( is_active )
                            $(jWrapId + " ul.t-resortsList li[value='" + children[i] + "']").addClass("active");
                        else
                            $(jWrapId + " ul.t-resortsList li[value='" + children[i] + "']").removeClass("active");
                    }
                }

                resetHotelsScroll();
                updatingWidget["hotels"]();
            });

            /****************************** CHANGE "HOTELS" ACTIONS ******************************/
            $("body").on("click", jWrapId + " ul.t-hotelsList li", function(){

                var isActive = $(this).hasClass("active");
                var $ul = $(this).closest('ul.t-hotelsList');
                var selected = $ul.data('selected') || [];

                if( isActive ){
                    $(this).removeClass("active");

                    var index = selected.indexOf(String($(this).val()));
                    if (index != - 1) {
                        selected.splice(index, 1);
                    }

                } else if (selected.length < 10) {
                    $(this).addClass("active");
                    selected.push(String($(this).val()));
                } else {
                    alert("Можно выбрать не более 10 отелей.");
                }

                $ul.data('selected', selected);

                if(selected.length){
                    $("a#selectedHotels", $wrapper).removeClass("non-access");
                } else {
                    $("a#selectedHotels", $wrapper).addClass("non-access");
                }
            });


            $(jWrapId + " div#hotels-group").on("scroll", function(e){
                if (e.target.scrollHeight - $(this).height() - 200 < e.target.scrollTop) {
                    var index_id = parseInt($(this).find("ul").data('index_id') || '0');

                    if (index_id !== -1) {
                        updatingWidget["hotels"](index_id);
                    }

                }
            });

            $("body").on("keyup", jWrapId + " input#searchHotelText", function(e){
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function(){updatingWidget["hotels"]();}, 32);
            });


            $("body").on("click", jWrapId + " a#selectedHotels", function(){

                if( $(this).hasClass("non-access") ){
                    return false;
                }

                $(this).addClass("non-active");
                $("a#allHotels", $wrapper).removeClass("non-active");

                updatingWidget["hotels"]();

                return false;
            });

            $("body").on("click", jWrapId + " a#allHotels", function(){

                $(this).addClass("non-active");
                $("a#selectedHotels", $wrapper).removeClass("non-active");

                updatingWidget["hotels"]();

                return false;
            });


            /****************************** CHANGE "OPERATORS" ACTION ******************************/
            $("body").on("click", jWrapId + " ul.t-operatorsList li", function(){
                //showLoadingFade("h");
                var isActive = $(this).hasClass("active");

                if( isActive ){
                    $(this).removeClass("active");
                } else {
                    $(this).addClass("active");
                }

                resetHotelsScroll();
                updatingWidget["hotels"]();
            });

            /****************************** "SEARCH" ACTION ******************************/
            $("body").on("click", jWrapId + " #buttonSearch", function(e){
                runSearch();
                return false;
            });

            /****************************** "REFRESH SEARCH" ACTION ******************************/
            $("body").on("click", jWrapId + " a.t-refreshResult", function(){
                tPager.redraw();
                $(document.body).trigger("click");
                $(this).hide();

                return false;
            });

            /****************************** CHANGE "VIEW MODE" ACTION ******************************/
            $("body").on("change", jWrapId + " input[name='viewtype']", function(){
                if( null !== tPager && $("div#xtourism-results", $wrapper).is(":visible") ){
                    var mode = $.toInt( $(this).val() );
                    tPager.setMode(mode);
                    $("a.t-refreshResult", $wrapper).hide();
                }

                return false;

            });

            /****************************** "SHOW TOURS" ACTION ******************************/
            $("body").on("click", jWrapId + " a.t-showTours", function(){
                var tr = $(this).closest("tr").next();
                var words = $(this).text().split(" ");

                if( tr.is(":hidden") ){
                    tr.show();
                    words[0] = "скрыть";
                } else {
                    tr.hide();
                    words[0] = "показать";
                }

                $(this).text(words.join(" "));
                return false;
            });

            /****************************** CHANGE "PAGE SIZE" ACTION ******************************/
            $("body").on("click", jWrapId + " div.t-toursResultPager a", function(){
                if( null !== tPager && !$(this).hasClass("active") ){
                    tPager.setSize( $(this).attr("ps") );
                    $("a.t-refreshResult", $wrapper).hide();
                }

                return false;
            });

            /****************************** CHANGE "PAGE" ACTION ******************************/
            $("body").on("click", jWrapId + " div.t-toursPager a", function() {

                if( null !== tPager && !$(this).hasClass("active") ){
                    tPager.setPage( $(this).text() );
                    $("a.t-refreshResult", $wrapper).hide();
                }

                return false;
            });

            $("body").on("click", jWrapId + " div#xtourism-expand a", function(){
                var $div = $(jWrapId + " div#xtourism");

                if( $div.hasClass("xtourism-expanded") ){
                    $div.removeClass("xtourism-expanded");
                    $("body").addClass("xtourism-minimized");
                } else {
                    $("body").removeClass("xtourism-minimized");
                    $div.addClass("xtourism-expanded");
                }

                return false;
            })

            //
            $(jWrapId + " select#country").trigger("change");

            sendMessageToParent({
                css: "/css/tsearch/spinners.css",
                h: getWindowHeight()
            });

            $("body").addClass("xtourism-" + $wrapper.attr("design-color"));

        });
    }

})(jQuery);