/**
 * Created by Arty on 17.02.2016.
 */


;(function($, undefined){

    var uid;
    var totalCount;
    var iframeId;

    var setWindowHeight = function(){
        parent.postMessage( JSON.stringify({
            iid: iframeId,
            h: $(document.body).outerHeight(true) + "px"
        }), "*");
    };

    // var setMaxBlockHeight = function(){
    //     var maxHeight = 0;
    //     $("#TourShowcaseStandardSettings_bg_color .caption").each(function(i){
    //         var height = parseInt( $(this).outerHeight(true) ) + parseInt( $(this).closest(".thumbnail").find(".thumbnail-showcase").outerHeight(true) );
    //         if( height > maxHeight ){
    //             maxHeight = height;
    //         }
    //     });
    //
    //     $("#TourShowcaseStandardSettings_bg_color .thumbnail").height(maxHeight);
    //     return maxHeight;
    // }

    var showcaseParams = function(){
        return $("#showcase-params").val();
    };

    var resizeShowcase = function(){
        $(window).trigger("resize");
    };

    var showSpinner = function(){
        // $("#TourShowcaseStandardSettings_bg_color div.t-tours-content").html('<div class="col-xs-12 text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw text-muted"></i><span class="sr-only">Загружаем туры...</span><hr></div>');
        $("#TourShowcaseStandardSettings_bg_color div.t-tours-content").html('<div class="col-xs-12 text-center lead"><span style="opacity: 0.6;">Загружаем туры...</span><hr></div>');
        $("#t_loading_tours").closest("div.text-center").hide();
        setWindowHeight();
    };

    var collectParams = function(page, with_resorts){
        return {
            "id": uid,
            "page": page,
            "cn": $(".t-shw-country").val(),
            "r": $(".t-shw-resort").val(),
            "ct": $(".t-shw-category").val(),
            "with_resorts": undefined !== with_resorts ? with_resorts : false
        };
    };

    var loadTours = function(page, with_resorts){

        var src = window.location.href;
        var shw_params = showcaseParams();
        if(shw_params){
            src = src.split("?")[0] + "?" + shw_params;
        }

        $.sendRequest(

            {url: src},
            collectParams(page, with_resorts),

            function(data){

                if( data == "expired"){
                    $(document.body).html('<div class="alert alert-warning text-center"><span class="glyphicon glyphicon-warning-sign"></span> Витрина туров временно недоступна</div>')
                    setWindowHeight();
                    return false;
                }

                var $toursContainer = $("#TourShowcaseStandardSettings_bg_color div.t-tours-content");

                data = JSON.parse(data);

                if( page > 1 ) {
                    $toursContainer.append( data.tours );
                } else {

                    totalCount = parseInt(data.totalCount);

                    if( !totalCount ){
                        $toursContainer.html('<div class="col-xs-12"><p class="text-center lead" style="opacity: 0.6;">Не найдено туров с данными параметрами</p><hr></div>');
                    } else {
                        $toursContainer.html( data.tours );
                    }
                }

                var $btn = $("#t_loading_tours");
                if (parseInt(data.count) >= totalCount) {
                    $btn.closest("div.text-center").hide();
                } else {
                    $btn.closest("div.text-center").show();
                    $btn.button("reset");
                    $btn.attr("page", page + 1);
                }

                if( undefined !== with_resorts && with_resorts ){
                    var options = ['<option value="0"></option>'];
                    for( var i=0, l=data.resorts.length; i<l; ++i ){
                        var resort = data.resorts[i];
                        options[i+1] = '<option value="' + resort.id + '">' + $.escapeHtml(resort.name) + '</option>';
                    }

                    $(".t-shw-resort").get(0).innerHTML = options.join("");
                }

                resizeShowcase();
            },
            "HTML",
            false
        );
    };

    self.onload = function(){
        //setMaxBlockHeight();
        setWindowHeight();
    };

    $(function(){

        window.TSHWCS = function(id, iframe_id, total){

            uid = id;
            totalCount = total;
            iframeId = iframe_id;

            $(window).resize(function(){
                // setMaxBlockHeight();
                setWindowHeight();
            });


            $("body").on("click", "#t_loading_tours", function(){
                var btn = $(this);
                var page = parseInt(btn.attr("page"));
                btn.button("loading");
                loadTours(page);
            });
        };

        $('body').on('click', '.t-open-target', function() {

            if( $(this).attr("target") == "_popup" ){
                parent.postMessage( JSON.stringify({
                    iid: iframeId,
                    action: "openPopup",
                    url: $(this).attr("href"),
                    name: "tour_info",
                    width: 1200
                }), "*");
                return false;
            } else {
                return true;
            }
        });

        $('body').on('change', '.t-shw-country', function() {
            $(".t-shw-resort").val(0);
            showSpinner();
            loadTours(1, true);
        });

        $('body').on('change', '.t-shw-resort', function() {
            showSpinner();
            loadTours(1);
        });

        $('body').on('change', '.t-shw-category', function() {
            showSpinner();
            loadTours(1);
        });

    });

})(jQuery);