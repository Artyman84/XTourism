/**
 * Created by Arty on 02.02.2016.
 */

;(function($, undefined){

    var showLoadingFade = function(cc, s) {

        if( !$("xtourism-ajax-loader").length ){

            $("body").append(
                '<div class="xtourism-ajax-loader xtourism-' + cc + ' xtourism-spinner-load' + s + '" style="position:absolute; z-index: 1;">' +
                    '<div class="xtourism-mini-spinner xtourism-spinner"></div>' +
                    '<div class="xtourism-spinner-label" style="font-size: 12px; margin-top:25px; position:relative;"><strong>Запрашиваем информацию о туре</strong></div>' +
                '</div>'
            ).find(".xtourism-ajax-loader").show();

            resizeFade();
        }
    }

    var hideLoadingFade = function () {

        if( $.toInt($("body .xtourism-ajax-loader").length) ){
            $( "body .xtourism-ajax-loader").fadeOut("hide", function(){$(this).remove();});
        }
    }

    var resizeFade = function(){
        if( $(".xtourism-ajax-loader").length ) {
            var $el = $("#tour-info");

            $(".xtourism-ajax-loader").css({
                top: $el.offset().top,
                left: $el.offset().left,
                width: $el.innerWidth(),
                height: 307
            });
            $(".xtourism-spinner-label").css("top", Math.ceil($(".xtourism-spinner").position().top));
        }
    }

    window.tour_request = function(data){

        $(function(){

            //$(".t-slick").slick({
            //    dots: true,
            //    draggable: false,
            //    slidesToShow: 7,
            //    slidesToScroll: 7
            //}).show();
            //
            //$("a.thumbnail").click(function() {
            //    $("#" + $(this).attr("img_id")).trigger("click");
            //    return false;
            //});
            //
            //$("body").tooltip({selector: "[data-toggle=tooltip]"});

            showLoadingFade(data.bg_color_class, data.spinner);

            $.sendRequest( "FrontSearcher/tourInfo", { "id": data.uid, "p": data.request_params }, function(response) {

                    if( response == "expired" || response == "" ) {
                        $(document.body).html('<div class="alert alert-warning text-center"><span class="glyphicon glyphicon-warning-sign"></span> Поиск туров временно недоступен</div>')
                        return false;
                    }

                    var info = JSON.parse(response);

                    if(undefined === info) {
                        $("body").html('<div class="alert alert-warning text-center"><strong><span class="glyphicon glyphicon-warning-sign"></span> Информация о данном туре временно недоступна. Обновите страницу или выбирите другой тур.</strong></div>');
                        return false;
                    }

                    hideLoadingFade();
                },
                "HTML",
                false
            );

        });

    }

    $(window).resize(function(e){
        resizeFade();
    });

})(jQuery);