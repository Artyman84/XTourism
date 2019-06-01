
/*************************** Migration Core ****************************/
;(function($, undefined){
    var extra;
    var url;
    var all_tables = ['countries', 'resorts', 'hotels', 'dep_cities', 'hotel_categories', 'meals', 'hotel_statuses', 'ticket_statuses'];

    var reloadOperatorData = {
        loadDepCities: function(html){
            $("tbody[table='operator_dep_cities']", getWrap()).closest("div.t-OperatorRow").html(html);
        },
        loadCountries: function(html){
            $("tbody[table='operator_countries']", getWrap()).closest("div.t-OperatorRow").html(html);
        },
        loadResorts: function(html){
            $("tbody[table='operator_resorts']", getWrap()).closest("div.t-OperatorRow").html(html);
        },
        loadDirResorts: function(html){
            $("tbody[table='resorts']", getWrap()).closest("div.t-DirectoryRow").html(html);
        },
        loadHotels: function(html){
            $("tbody[table='operator_hotels']", getWrap()).closest("div.t-OperatorRow").html(html);
        },
        loadDirHotels: function(html){
            $("tbody[table='hotels']", getWrap()).closest("div.t-DirectoryRow").html(html);
        },
        loadHotelCategories: function(html){
            $("tbody[table='operator_hotel_categories']", getWrap()).closest("div.t-OperatorRow").html(html);
        },
        loadMeals: function(html){
            $("tbody[table='operator_meals']", getWrap()).closest("div.t-OperatorRow").html(html);
        },
        loadHotelStatuses: function(html){
            $("tbody[table='operator_hotel_statuses']", getWrap()).closest("div.t-OperatorRow").html(html);
        },
        loadTicketStatuses: function(html){
            $("tbody[table='operator_ticket_statuses']", getWrap()).closest("div.t-OperatorRow").html(html);
        },
        comboCountries: function(html){
            var $combo = $("select#tCountriesId", getWrap());
            var disabled = $combo.is(":disabled");
            var $label = $combo.prev();

            $combo.remove();
            $label.after(html);
            $("select#tCountriesId", getWrap()).attr("disabled", disabled);

        },
        comboResorts: function(html){
            var $combo = $("select#tResortsId", getWrap());
            var disabled = $combo.is(":disabled");
            var $label = $combo.prev();

            $combo.remove();
            $label.after(html);
            $("select#tResortsId", getWrap()).attr("disabled", disabled);
        },
        statisticByOperator: function(html){
            $('label[for="tOperatorsId"]', getWrap()).next().html(html);
        },
        statisticByCountry: function (html){
            $('label[for="tCountriesId"]', getWrap()).next().html(html);
        },
        statisticByResort: function (html){
            $('label[for="tResortsId"]', getWrap()).next().html(html);
        },
        unreadElements: function (unread_elements){

            for(var table in unread_elements){
                var $badge = $('a[tab-table="' + table + '"] span.badge', getWrap());

                if( parseInt(unread_elements[table]) ) {

                    if( $badge.length ){
                        $badge.text(unread_elements[table]);
                    } else {
                        $('a[tab-table="' + table + '"]', getWrap()).append('<span class="badge">' + unread_elements[table] + '</span>');
                    }

                } else if( $badge.length ){
                    $badge.remove();
                }
            }
        }
    };

    var getWrapID = function(){
        return extra.wrapID;
    }

    var getWrap = function(){
        return $("#" + extra.wrapID);
    }

    var getTabParams = function(oTable){
        var params = null;

        if( oTable === undefined ){
            oTable = getCurrentOTable();
        }

        if( oTable == "operator_resorts" && getCurrentCountry() ){
            params = {country: getCurrentCountry()};
        }

        if( oTable == "operator_hotels" && getCurrentResort() ){
            params = {resort: getCurrentResort()};
        }

        return params;
    }

    var getCurrentOperatorId = function(){
        return $("#" + getWrapID() + " select#tOperatorsId").val();
    }

    var getCurrentCountry = function(){
        return $("select#tCountriesId", getWrap()).val();
    }

    var getCurrentResort = function(){
        return $("select#tResortsId", getWrap()).val();
    }

    var getCurrentOTable = function(){
        return $("#tabsWrapperId div.tab-content div.active div.t-OperatorRow tbody", getWrap()).attr("table");
    }

    var getCurrentDTable = function(){
        return $("#tabsWrapperId div.tab-content div.active div.t-DirectoryRow tbody", getWrap()).attr("table");
    }

    var getBadgeCount = function(){
        return $.toInt($('a[tab-table="' + getCurrentDTable() + '"] span.badge', getWrap()).text());
    }

    var setBadgeCount = function(badge_count){
        var table = getCurrentDTable();
        var data = {};
        data[table] = badge_count;
        reloadOperatorData['unreadElements'](data);

        if( table == 'hotels' || table == 'resorts' ) {

            var $tr_countries = $("tbody[table='operator_countries'] tr[elementid='" + getCurrentCountry() + "']", getWrap());

            if( table == 'hotels' ) {

                var $tr_resorts = $("tbody[table='operator_resorts'] tr[elementid='" + getCurrentResort() + "']", getWrap());
                var resort_badge_count_h = $.toInt($tr_resorts.find("td:eq(2) span.badge").text());
                var country_badge_count_h = $.toInt($tr_countries.find("td:eq(3) span.badge").text());

                if (badge_count) {
                    $tr_resorts.find("td:eq(2)").html('<span class="badge">' + badge_count + '</span>');
                } else {
                    $tr_resorts.find("td:eq(2)").html('');
                }

                var country_badge_count = country_badge_count_h - resort_badge_count_h + badge_count

                if( country_badge_count ) {
                    $tr_countries.find("td:eq(3)").html('<span class="badge">' + (country_badge_count_h - resort_badge_count_h + badge_count) + '</span>');
                } else {
                    $tr_countries.find("td:eq(3)").html('');
                }

            } else if( table == 'resorts' ) {
                if (badge_count) {
                    $tr_countries.find("td:eq(2)").html('<span class="badge">' + badge_count + '</span>');
                } else {
                    $tr_countries.find("td:eq(2)").html('');
                }
            }
        }
    }

    var getIsUnreadElement = function(eid){
        return $("tbody[table='" + getCurrentOTable() + "'] tr[elementid='" + eid + "']", getWrap()).hasClass("info");
    }

    var getUnreadElements = function(){
        var elements = [];
        $("tbody[table='" + getCurrentOTable() + "'] tr[directoryid='0'][class~='info']", getWrap()).each(function(i){
            elements.push($(this).attr("elementid"));
        });

        return elements;
    }

    var relatedStatus = function(oTable){
        if( oTable === undefined ){
            oTable = getCurrentOTable();
        }

        var $related = $("tbody[table='" + oTable + "']", getWrap()).closest("div.t-OperatorRow").find("div#relatedStatusId .active");

        if( $related.hasClass("t-allElements") ){
            return null;
        }

        if( $related.hasClass("t-relatedElements") ){
            return 1;
        }

        if( $related.hasClass("t-freeElements") ){
            return 0;
        }

        return null;
    }

    var reloadHtmlData = function(data){
        $.each(data, function(loadMethod, content){
            reloadOperatorData[loadMethod](content);
        });
    }

    var loadOperatorData = function(params, allowed){
        var forbidden = {
            "dep_cities": 1, "countries": 1, "resorts": 1, "directory_resorts": 1,
            "directory_hotels": 1, "hotels": 1, "hotel_categories": 1, "meals": 1,
            "hotel_statuses": 1, "ticket_statuses": 1, "comboCountries": 1, "comboResorts": 1,
            "statistic_by_operator": 1, "statistic_by_country": 1, "statistic_by_resort": 1
        };

        if( $.type(allowed) == "array" ){

            var tables = [];
            for( var i= 0, l=allowed.length; i<l; ++i ){
                delete forbidden[allowed[i]];

                if( $.inArray(allowed[i], all_tables) != -1 ){
                    tables = allowed[i];
                }
            }

            params.forbidden = forbidden;
        }

        $.sendRequest("Migration/loadOperatorData", params, function(data){
            reloadHtmlData(data);
            updateSynchronizeButton(tables);
        });
    };

    var recountElements = function(table){
        $("tbody[table='" + table + "'] tr span.t-number", getWrap()).each(function(i,v){
            $(this).html(i + 1);
        });
    };

    var updateSynchronizeButton = function(tables){
        if( tables == undefined ){
            tables = all_tables;
        }

        for( var i=0, l=tables.length; i<l; ++i ){
            if( getFreeElements('operator_' + tables[i]).length ) {
                $("div[id^='" + tables[i] + "_'] .t-synchronizeElements", getWrap()).removeClass("disabled");
            } else {
                $("div[id^='" + tables[i] + "_'] .t-synchronizeElements", getWrap()).addClass("disabled");
            }
        }
    };

    var blockDictionaryElement = function(id, table){
        $.sendRequest("Migration/blockDirectoryElement", {id: id, table: table}, function(ret){

            var $tr = $("tbody[table='" + table + "'] tr[directoryid='" + id + "']", getWrap());
            var $i = $("a.t-block span", $tr);
            var title = "Заблокировать";
            var cc = 'text-danger';

            if( ret.icon == "ban-circle" ){
                $tr.removeClass("t-el-disabled");
            } else {
                title = "Разблокировать";
                $tr.addClass("t-el-disabled");
                cc = 'text-success';
            }

            $i.attr("class", cc + " glyphicon glyphicon-" + ret.icon);
            $i.parent().attr("data-original-title", title);
        });
    }

    var setReadStatus = function(el_ids, table){
        var elements = {};
        for(var i=0, l=el_ids.length; i<l; ++i){
            elements['_' + el_ids[i]] = getIsUnreadElement(el_ids[i]) ? 0 : 1
        }

        $.sendRequest("Migration/setReadStatus", {oid: getCurrentOperatorId(), elements: elements, table: table}, function(element_ids){
            var badge_count = getBadgeCount();

            for(var i=0, l=element_ids.length; i<l; ++i){
                var el_id = element_ids[i];

                if (elements['_' + el_id]) {
                    $("tbody[table='" + table + "'] tr[elementid='" + el_id + "']", getWrap()).addClass("info");
                    badge_count += 1;
                } else {
                    $("tbody[table='" + table + "'] tr[elementid='" + el_id + "']", getWrap()).removeClass("info");
                    badge_count += -1;
                }

            }

            setBadgeCount(badge_count);
        });
    }


    var selectSuccessOperatorElements = function(oTable, directoryId){
        var $trs = $("tbody[table='" + oTable + "']", getWrap()).find("tr[directoryid='" + directoryId + "']");
        $trs.addClass("selected");
    }

    var selectDirectoryElement = function(dTable, directoryId){
        var $tr = $("tbody[table='" + dTable + "']", getWrap()).find("tr[directoryid='" + directoryId + "']");
        $tr.addClass("selected");
    }

    var selectElement = function(oTable, dTable, directoryId){
        selectDirectoryElement(dTable, directoryId);
        selectSuccessOperatorElements(oTable, directoryId);
    }

    var unselectElements = function(){
        $("tbody[table='" + getCurrentDTable() + "'] tr[class*='selected'], tbody[table='" + getCurrentOTable() + "'] tr[class*='selected']", getWrap()).removeClass("selected");
    }

    var getSelectedDirectoryID = function(dTable){
        var $tr = $("tbody[table='" + dTable + "']", getWrap()).find("tr.selected");
        return parseInt($tr.length) ? $tr.attr("directoryid") : null;
    }

    var changeComboColor = function(table, elements, bind){
        var ids = {"countries": "tCountriesId", "resorts": "tResortsId"};
        var color = bind ? "" : "red";

        if( table == "resorts" || table == "countries" ){
            for(var i= 0, l=elements.length; i<l; ++i){
                $("select#" + ids[table], getWrap()).find("option[value='" + elements[i] + "']").css("color", color);
            }
        }
    }

    var triggerComboEvent = function(table, elements){

        var allowed = ["statistic_by_operator", "statistic_by_country", "statistic_by_resort"];

        // Если скрестили страну, которая выбрана в дропдауне стран
        if( table == "countries" ){

            var country = getCurrentCountry(), country_match=false;
            for(var i=0, l=elements.length; i<l; ++i ){
                if( country == elements[i] ){

                    var params = { "id": getCurrentOperatorId(), "country": country, "resort": getCurrentResort()};
                    allowed = allowed.concat(["comboResorts", "resorts", "directory_resorts", "hotels", "directory_hotels"]);

                    loadOperatorData(params, allowed);

                    country_match = true;
                    break;
                }
            }


        // Если скрестили курорт, который был выбран в дропдауне курортов
        } else if( table == "resorts" ){

            var resort = getCurrentResort(), resort_match=false;
            for(var i=0, l=elements.length; i<l; ++i ){
                if( resort == elements[i] ){

                    var params = { "id": getCurrentOperatorId(), "country": getCurrentCountry(), "resort": resort };
                    allowed = allowed.concat(["hotels", "directory_hotels"]);

                    loadOperatorData( params, allowed );

                    resort_match = true;
                    break;
                }
            }

        }

        if( !resort_match && !country_match && (table == "countries" || table == "resorts") ) {
            loadOperatorData( {"id": getCurrentOperatorId(), "country": getCurrentCountry(), "resort": getCurrentResort()}, allowed );
        }
    }

    var bindElements = function(operatorId, table, elements, directoryId){
        var params = {
            operatorId: operatorId,
            elements: elements,
            table: table
        };

        var unread_elements = 0;
        for( var i=0, l=elements.length; i<l; ++i ){
            if( getIsUnreadElement(elements[i]) ){
                ++unread_elements;
            }
        }


        if( undefined !== directoryId ){
            params.directoryId = directoryId;
        }

        $.sendRequest("Migration/bindElements", params, function(data){

            var related = relatedStatus();
            var relatedCount = 0;

            for( var i=0, l=data.length; i<l; ++i ){
                var $tr = $("tbody[table='" + "operator_" + table + "'] tr[elementid='" + data[i].element_id + "']", getWrap());

                if( related === null ){
                    $tr.removeClass("info").addClass("success").attr("directoryid", data[i].directory_id);
                    $tr.find("td:last span.fa").attr("class", "fa fa-unlink text-danger");
                    $tr.find("td:last a.t-bindElement").attr({"class": "t-unbindElement", "data-original-title": "Разъединить"});
                } else {
                    $tr.remove();
                }

                relatedCount++;
            }

            // Поменять статистики отелей для таблицы "hotels"
            if( table == "hotels" ) {
                changeHotelsStatistics(relatedCount);
            }

            // Пересчитать элемнеты оператора
            recountElements("operator_" + table);

            // Вызвать событие "change" для стран/курортов
            triggerComboEvent(table, params.elements);

            // Поменять цвет элементов в дропдауне
            var elements = []
            for( var i in params.elements){
                var $el = $("tbody[table='" + "operator_" + table + "'] tr[elementid='" + params.elements[i] + "']", getWrap());

                if( $.toInt($el.attr("directoryid")) ){
                    elements[elements.length] = params.elements[i];
                }
            }

            changeComboColor(table, elements, true);

            // Отметить только что скрещенный(ые) элемент(ы) справочника отелей
            if( table == 'hotels' ) {
                if (undefined !== directoryId) {
                    $("tbody[table='hotels'] tr[directoryid='" + directoryId + "']", getWrap()).addClass("success");
                } else {
                    $("tbody[table='" + "operator_hotels'] tr[directoryid!='0']", getWrap()).each(function (i) {
                        $("tbody[table='hotels'] tr[directoryid='" + $(this).attr("directoryid") + "']", getWrap()).addClass("success");
                    });
                }
            }

            // Лочить/Анлочить кнопку синхронизации по сравнению
            updateSynchronizeButton([table]);

            // После скрещивания уменьшить число непрочитанных элементов
            // Если только что скрещенный элемент был в числе непрочитанных.
            if( unread_elements ) {
                setBadgeCount(getBadgeCount() - unread_elements);
            }
        });

    };

    var unbindElement = function(operatorId, elementId, oTable){
        var table = oTable.replace("operator_", "");
        var data = {
            operatorId: operatorId,
            elementId: elementId,
            table: table
        };

        $.sendRequest("Migration/unbindElement", data, function(is_unbined){
            if( oTable !== undefined && parseInt(is_unbined) ){
                var $tr = $("tbody[table='" + oTable + "'] tr[elementid='" + elementId + "']", getWrap());
                var related = relatedStatus();

                // Убрать класс t-success у элемента справочника отеля
                if( table == 'hotels' ) {
                    var directory_id = $("tbody[table='" + oTable + "'] tr[elementid='" + elementId + "']", getWrap()).attr("directoryid");
                    $("tbody[table='hotels'] tr[directoryid='" + directory_id + "']", getWrap()).removeClass("success");

                    // Поменять статистики отелей для таблицы "hotels"
                    changeHotelsStatistics(-1);
                }

                if( related === null ){
                    $tr.removeClass("success").attr("directoryid", 0);
                    $tr.find("td:last span.fa").attr("class", "fa fa-link text-success");
                    $tr.find("td:last a.t-unbindElement").attr({"class": "t-bindElement", "data-original-title": "Связать"});
                } else {
                    $tr.remove();
                }


                recountElements(oTable);

                // Вызвать событие "change" для стран/курортов
                triggerComboEvent(table, [elementId]);

                // Поменять цвет элементов в дропдауне
                changeComboColor(table, [elementId], false);

                // Лочить/Анлочить кнопку синхронизации по сравнению
                updateSynchronizeButton([table]);
            }
        });
    };

    var filterOperatorElements = function(operatorId, table, related){
        var data = {operatorId: operatorId, table: table};

        if( related !== null ){
            data.related = related;
        }

        var params = getTabParams();
        if( params !== null ){
            data.params = params;
        }

        $.sendRequest("Migration/filterOperatorElements", data, function(data){
            $("tbody[table='operator_" + table + "']", getWrap()).closest("div.t-OperatorRow").html( data.html );
        });
    };

    var getFreeElements = function(table){
        if( table === undefined ){
            table = getCurrentOTable();
        }

        var elements = [];
        $("tbody[table='" + table + "'] tr[directoryid='0']", getWrap()).each(function(){
            elements[elements.length] = $(this).attr('elementid');
        });

        return elements;
    };

    var changeHotelsStatistics = function(delta){

        var list = ["tOperatorsId", "tCountriesId", "tResortsId"];

        for( var i=0, l=list.length; i<l; ++i ) {

            var $total = $("label[for='" + list[i] + "']", getWrap()).next().find(".t-hotels-statistic-total"),
                $related = $("label[for='" + list[i] + "']", getWrap()).next().find(".t-hotels-statistic-related span");

            var total, related, percents, related_class;

            if ($total.length && $total.text()) {

                total = $.toInt($total.text());
                related = $.toInt($related.text()) + delta;
                percents = Math.round(related / total * 100);

                if (percents < 50) {
                    related_class = 'text-danger';
                } else if (percents < 90) {
                    related_class = 'text-warning';
                } else {
                    related_class = 'text-success';
                }

                $related.parent().removeClass().addClass(related_class + " t-hotels-statistic-related").html('<span>' + related + '</span> (' + percents + '%)')
            }
        }

    }

    /*************************** Migration Actions ****************************/

    window.initMigrationCore = function(migrationSettings){
        extra = migrationSettings.extra;
        url = migrationSettings.url;

        $(function(){
            $("body").on("change", "#" + getWrapID() + " select#tOperatorsId", function(){
                loadOperatorData({ "id": $(this).val() });
            });

            $("body").on("change", "#" + getWrapID() + " select#tCountriesId", function(){
                var params = { "id": getCurrentOperatorId(), "country": $(this).val() };
                var allowed = ["comboResorts", "resorts", "directory_resorts", "hotels", "directory_hotels", "statistic_by_country", "statistic_by_resort"];

                loadOperatorData(params, allowed);
            });

            $("body").on("change", "#" + getWrapID() + " select#tResortsId", function(){
                var params = { "id": getCurrentOperatorId(), "resort": $(this).val() };
                var allowed = ["hotels", "directory_hotels", "statistic_by_resort"];

                loadOperatorData( params, allowed );
            });

            $("body").on("click", "#" + getWrapID() + " a.t-block", function(){
                var directoryId = $(this).closest("tr").attr("directoryid");
                var table = $(this).closest("tbody").attr("table");
                blockDictionaryElement(directoryId, table);
                return false;
            });

            $("body").on("click", "#" + getWrapID() + " tbody[table='hotels'] tr td a.t-edit", function(){
                var category_id = $(this).closest("tr").attr("data-category-id");
                var $td = $(this).closest("tr").find("td.t-dirName");
                var name = $td.find("a:last").text();
                $("body").data("hotel-data", $td.html());

                var select_cat = '<select class="form-control input-sm" style="width: 60px;">';
                for(var i=0,l=migrationSettings['extra']['dir_hotel_categories'].length; i<l; ++i){
                    var c = migrationSettings['extra']['dir_hotel_categories'][i];
                    select_cat += '<option value="' + c.id + '" ' + (category_id == c.id ? 'selected="selected"' : '') + '>' + $.escapeHtml(c.name) + '</option>';
                }
                select_cat += '</select>';

                $td.parent().find("td:last a:first").removeClass("t-edit").addClass("t-cancel").attr("data-original-title", "Отменить").find("span").removeClass("glyphicon-edit").addClass("glyphicon-ban-circle");

                $td.html('<div class="input-group">' +
                            '<input type="text" class="form-control input-sm t-hotel-new-name" style="width: 220px;" value="' + $.escapeHtml(name) + '">' +
                            '<div class="input-group">' +
                                select_cat +
                                '<span class="input-group-btn"><a href="#" class="t-save btn btn-default btn-sm" type="button"><span class="fa fa-save text-info"></span></a></span>' +
                            '</div>' +
                        '</div>');

                $td.find("input:first").focus();

                return false;
            });

            $("body").on("click", "#" + getWrapID() + " tbody[table='hotels'] tr td a.t-cancel", function(){
                var $td = $(this).closest("tr").find("td.t-dirName");
                $td.parent().find("td:last a:first").removeClass("t-cancel").addClass("t-edit").attr("data-original-title", "Редактировать").find("span").removeClass("glyphicon-ban-circle").addClass("glyphicon-edit");
                $td.html($("body").data("hotel-data"));

                return false;
            });

            $("body").on("keyup", "#" + getWrapID() + " tbody[table='hotels'] tr td input.t-hotel-new-name", function(e){
                if(e.keyCode == 13){
                    $(this).closest("td").find("a.t-save").click();
                }
                return false;
            });

            $("body").on("click", "#" + getWrapID() + " tbody[table='hotels'] tr td a.t-save", function(){
                var $td = $(this).closest("tr").find("td.t-dirName");
                var cat_name = $td.find("select:first option:selected").text();
                var data = {
                    "id": $(this).closest("tr").attr("directoryid"),
                    "name": $td.find("input:first").val(),
                    "category_id": $td.find("select:first").val()
                };


                $.sendRequest('Migration/saveDirectoryHotel', data, function(status){

                    $td.html($("body").data("hotel-data"));
                    $td.closest("tr").find("td:last a:first").removeClass("t-cancel").addClass("t-edit").attr("data-original-title", "Редактировать").find("span").removeClass("glyphicon-ban-circle").addClass("glyphicon-edit");

                    if( parseInt(status) ) {
                        $td.find("a:last").text(data.name);
                        $td.find("span.t-cat").text(cat_name == "Apartment" ? 'Apt' : cat_name + '*');
                        $td.closest("tr").attr("data-category-id", data.category_id);
                    }
                });

                return false;
            });

            // Bind element
            $("body").on("click", "#" + getWrapID() + " a.t-bindElement", function(){
                var $tr = $(this).closest("tr");
                var table = $(this).closest("tbody").attr("table").replace("operator_", "");
                var elements = [$tr.attr("elementid")];
                var operatorId = getCurrentOperatorId();
                var directoryId = getSelectedDirectoryID(table);

                if( directoryId === null ){
                    alert("Пожалуйста, выберите элемент из справочника");
                } else {
                    bindElements(operatorId, table, elements, directoryId);
                }

                return false;
            });

            // Bind elements
            $("body").on("click", "#" + getWrapID() + " .t-synchronizeElements", function(){

                if( $(this).attr("disabled") == "disabled" ){
                    return false;
                }

                var freeElements = getFreeElements();

                if( !parseInt(freeElements.length) ){
                    alert("Нет свободных элементов");
                    return;
                }

                var operatorId = getCurrentOperatorId();
                var table = getCurrentDTable();

                if (confirm("Вы собираетесь скрестить выбранные элементы Тур оператора с идентичными элементами Справочника?")) {
                    bindElements(operatorId, table, freeElements);
                }

                return false;
            });

            // UnBind element
            $("body").on("click", "#" + getWrapID() + " a.t-unbindElement", function(){
                var $tr = $(this).closest("tr");
                var elementId = $tr.attr("elementid");
                var operatorId = getCurrentOperatorId();
                var oTable = $(this).closest("tbody").attr("table");

                if( confirm("Вы уверены, что хотите разъединить этот элемент?") ){
                    unbindElement(operatorId, elementId, oTable);
                }

                return false;
            });

            $("body").on("click", "#" + getWrapID() + " .t-allElements, .t-relatedElements, .t-freeElements", function(){
                if( $(this).hasClass("active") ){
                    return;
                }

                var operatorId = getCurrentOperatorId();
                var table = $(this).closest("div.t-OperatorRow").find("table.t-oTable tbody").attr("table").replace("operator_", "");
                var related = null;

                if( $(this).hasClass("t-relatedElements") ){
                    related = 1;
                } else if( $(this).hasClass("t-freeElements") ){
                    related = 0;
                }

                filterOperatorElements(operatorId, table, related);
            });


            var searchTimer = null;
            $("body").on("keyup", "#" + getWrapID() + " input.t-directoryFilter", function(){
                var $self = $(this);

                clearTimeout(searchTimer);

                searchTimer = setTimeout(function () {
                    var searchText = $self.val();
                    var matcher = searchText ? new RegExp(searchText.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&"), "i") : null;
                    var elements = $self.closest("div.col-md-6").find("div.t-scroll tbody td.t-dirName");
                    var $body = elements.closest('tbody');
                    var i = 1;

                    $body.css('display', 'none');

                    $.each(elements, function(k, v){
                        var name = $(v).text();
                        var $tr = $(this).parent();

                        if ((!matcher || matcher.test(name))) {
                            $tr.find("td:first span").text(i++);
                            $tr.css("display", "");
                        } else {
                            $tr.css("display", "none");
                        }

                    });

                    $body.css('display', '');

                }, 170);

            });




            /***********************************************  Handle events  ************************************************/

            // При клике на элемент справочника ДИРЕКТОРИИ - прокручивать к соответствующему  элементу оператора
            $("body").on("click", "#" + getWrapID() + " div.t-DirectoryRow td.directory-handle", function(){

                var dTable = $(this).closest("tbody").attr("table");
                var oTable = "operator_" + dTable;
                var directoryId = $(this).closest("tr").attr("directoryid");

                unselectElements();
                selectElement(oTable, dTable, directoryId);

                var yOrigin = $("tbody[table='" + oTable + "']", getWrap()).position().top;
                var yOffset = $("tbody[table='" + oTable + "'] tr[directoryid='" + directoryId + "']:first", getWrap());

                if(yOffset.length){
                    yOffset = yOffset.position().top;
                    $("tbody[table='" + oTable + "']", getWrap()).closest("div.t-scroll").scrollTop( yOffset - yOrigin - 170 );
                }

            });

            // При клике на элемент справочника ОПЕРАТОРА - прокручивать к соответствующему элементу справочника директории
            $("body").on("click", "#" + getWrapID() + " div.t-OperatorRow td.directory-handle", function(){
                var directoryId = $.toInt($(this).closest("tr").attr("directoryid"));
                var oTable = $(this).closest("tbody").attr("table");
                var dTable = oTable.replace("operator_", "");

                unselectElements();
                if( directoryId ) {

                    selectElement(oTable, dTable, directoryId);

                    var yOrigin = $("tbody[table='" + dTable + "']", getWrap()).position().top;
                    var yOffset = $("tbody[table='" + dTable + "'] tr[directoryid='" + directoryId + "']", getWrap()).position().top;

                    $("tbody[table='" + dTable + "']", getWrap()).closest("div.t-scroll").scrollTop(yOffset - yOrigin - 170);

                } else {

                    setReadStatus( [$(this).closest("tr").attr("elementid")], oTable );
                }

            });

            $("body").on("click", "#" + getWrapID() + " .t-updateOperatorData", function(){
                loadOperatorData({ "id": getCurrentOperatorId(), "update": 1 });
                return false;
            });

            $("body").on("click", "#" + getWrapID() + " .t-mark-elements_read", function(){
                var unreadElements = getUnreadElements();
                if( unreadElements.length ){
                    setReadStatus( unreadElements, getCurrentOTable() );
                } else {
                    alert("Нет непросмотренных элементов");
                }
                return false;
            });

            updateSynchronizeButton();
        });
    };
})(jQuery);

