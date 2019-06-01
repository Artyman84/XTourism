/**
 * Created with JetBrains PhpStorm.
 * User: Arti
 * Date: 07.07.14
 * Time: 18:57
 * To change this template use File | Settings | File Templates.
 */

;(function($, undefined){

    var $wrapper;
    var jWrapId;

    /***************************** GETTERS ********************************/

    var getDepCity = function(){
        return $("select#depCity", $wrapper).val();
    };

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

    var getNightFrom = function(){
        return $("select#nightFrom", $wrapper).val();
    };

    var getNightTo = function(){
        return $("select#nightTo", $wrapper).val();
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

    var getAvailableDateFrom = function(){
        return $("input#availableDateFrom", $wrapper).val();
    };

    var getAvailableDateTo = function(){
        return $("input#availableDateTo", $wrapper).val();
    };

    var getCheckedOperators = function(){
        var elements = document.querySelectorAll(jWrapId + " ul.t-operatorsList li.active"),
            operators = [];

        for(var i= 0,l=elements.length; i<l; ++i){
            operators[i] = String(elements[i].value);
        }

        return operators;
    }

    var getSearchOperators = function(){
        var operators = getCheckedOperators();

        if( !operators.length ){
            var elements = document.querySelectorAll(jWrapId + " ul.t-operatorsList li");
            for(var i= 0,l=elements.length; i<l; ++i){
                operators[i] = String(elements[i].value);
            }
        }

        return operators;
    }


    var getTourParams = function() {

        var params = {
            "operators": getSearchOperators(),
            "dirDepCity": getDepCity(),
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
            "currency": getCurrency()
        };

        return params;
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////


    window.HSearcher = function(_wrapperId, uid, operators, operators_by_dep_cities){

        jWrapId = "div#" + _wrapperId;
        $wrapper = $("#" + _wrapperId);

        $(function(){

            /****************************** "SEARCH" ACTION ******************************/
            $("body").on("click", jWrapId + " #buttonSearch", function(e){
                TResult.runSearch(getTourParams());
                return false;
            });

            $("body").on("change", jWrapId + " select#depCity", function(){
                var dep_city = "_" + $(this).val();
                var operatorDropDown = $("ul.t-operatorsList", $wrapper).get(0);

                if( operators_by_dep_cities.hasOwnProperty(dep_city) && operatorDropDown ){

                    var li,
                        fragment = document.createDocumentFragment(),
                        checked_operators = getCheckedOperators();

                    for( var i= 0,l=operators.length; i<l; ++i ){
                        if( $.inArray(operators[i].id, operators_by_dep_cities[dep_city]) != -1 ){
                            li = document.createElement('li');
                            li.value = operators[i].id;
                            li.className = $.inArray(operators[i].id, checked_operators) != -1 ? "active": "";
                            li.innerHTML = '<i></i>' + $.escapeHtml(operators[i].name);
                            fragment.appendChild(li);
                        }
                    }

                    operatorDropDown.innerHTML = "";
                    operatorDropDown.appendChild(fragment);
                    TResult.setOperatorsCount($(operatorDropDown).find("li").length);
                }
            });

            $("body").on("click", jWrapId + " ul.t-operatorsList li", function(){
                var isActive = $(this).hasClass("active");

                if( isActive ){
                    $(this).removeClass("active");
                } else {
                    $(this).addClass("active");
                }

            });

            $("body").on("click", jWrapId + " .t-head-mini-filter, " + jWrapId + " .t-head-mini-filter-icon", function(){
                $(".t-body-mini-filter").collapse("toggle");

                if( $(jWrapId + " .t-head-mini-filter").attr("aria-expanded") == "true" ){
                    $(jWrapId + " .t-head-mini-filter-icon").removeClass("glyphicon-triangle-left").addClass("glyphicon-triangle-bottom");
                } else {
                    $(jWrapId + " .t-head-mini-filter-icon").removeClass("glyphicon-triangle-bottom").addClass("glyphicon-triangle-left");
                }
                return false;
            });

            $("body").on("click", ".t-tour-request", function(){
                $("#modalTourRequest #requestMessageContainer").hide();
                $("#modalTourRequest form").attr("action", $.createUrl("FrontSearcher/tourRequest", {id: uid, p: $(this).attr("rp")})).show();
                $("#modalTourRequest #RequestForm_name, #RequestForm_phone, #RequestForm_email, #RequestForm_comment").val("");
                $("#modalTourRequest div.has-success").removeClass("has-success");
                $("#modalTourRequest div.has-error").removeClass("has-error");
                $("#modalTourRequest div.text-danger").hide();
                $("#modalTourRequest [type='submit']").show();

                $('#modalTourRequest').modal({});
            });

        });
    }

})(jQuery);