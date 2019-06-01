/**
 * Created by Arti on 15.06.2015.
 */

;(function($, undefined){

    var lastSettings;
    var defaultSettings;

    window.setDefaultSettings = function(settings){
        defaultSettings = settings;

    }

    window.setLastSettings = function(settings){
        lastSettings = settings;
    }

    var tourBlockClasses = function(perRow){
        switch(perRow) {
            case 0: default: return 'col-lg-3 col-md-4 col-sm-6 col-xs-6 t-blockTour';
            case 2: return 'col-lg-6 col-md-6 col-sm-6 col-xs-6 t-blockTour';
            case 3: return 'col-lg-4 col-md-4 col-sm-4 col-xs-4 t-blockTour';
            case 4: return 'col-lg-3 col-md-3 col-sm-3 col-xs-3 t-blockTour';
        }
    }

    var showcaseFrameDoc = function(){
        return $.getFrameDocument("xtrsmproduct");
    }

    var setSettings = function(settings){

        $.each(settings, function(name, value){
            var el = $("#TourShowcaseStandardSettings_" + name);

            if( el.hasClass("miniColors") ) {
                el.miniColors('value', value);
            } else {
                el.val(value);
            }

        });
    }


    var reloadShowcase = function(params) {
        if( params === undefined ){
            params = $("form#showcase-settings-form").serialize();
        }

        var iframeDoc = showcaseFrameDoc();
        var src = iframeDoc.location.href + "&" + params;

        if( src != iframeDoc.location.href ) {
            $.showFade();
            iframeDoc.location.href = src;
        }
    }

    var resizeShowcaseWindow = function() {
        $("iframe[name='xtrsmproduct']").css("height", $.getFrameDocument("xtrsmproduct").body.offsetHeight + 'px');
    }

    $(function(){


        /***********************************************  SETTINGS  ***********************************************/

        // $("body").on("change", "#TourShowcaseStandardSettings_per_row", function(){
        //     var tourClasses = tourBlockClasses(parseInt($(this).val()));
        //     $("div.t-blockTour", showcaseFrameDoc()).attr("class", tourClasses);
        //     resizeShowcaseWindow();
        // });

        $("body").on("change", "#TourShowcaseStandardSettings_open_tour_target", function(){
            $(".t-open-target", showcaseFrameDoc()).attr("target", $(this).val());
        });

        $("body").on("change", "#TourShowcaseStandardSettings_rounding", function(){
            $("#TourShowcaseStandardSettings_bg_color", showcaseFrameDoc()).css("border-radius", $(this).val() + "px");
        });




        $("body").on("change", "#TourShowcaseStandardSettings_bg_color", function(){
            $("#TourShowcaseStandardSettings_bg_color", showcaseFrameDoc()).css("background-color", $(this).val());
        });

        $("body").on("change", "#TourShowcaseStandardSettings_bg_block_color", function(){
            $(".t-bg-block", showcaseFrameDoc()).css("background-color", $(this).val());
        });

        $("body").on("change", "#TourShowcaseStandardSettings_tour_link_color", function(){
            $(".t-tour-link", showcaseFrameDoc()).css("color", $(this).val());
        });

        $("body").on("change", "#TourShowcaseStandardSettings_icon_color", function(){
            $(".t-icon", showcaseFrameDoc()).css("color", $(this).val());
        });

        $("body").on("change", "#TourShowcaseStandardSettings_price_label_color", function(){
            $(".t-price-label", showcaseFrameDoc()).css("background-color", $(this).val());
        });

        $("body").on("change", "#TourShowcaseStandardSettings_price_color", function(){
            $(".t-price", showcaseFrameDoc()).css("color", $(this).val());
        });

        $("body").on("change", "#TourShowcaseStandardSettings_pagination_color", function(){
            $(".t-pagination-button", showcaseFrameDoc()).css("background-color", $(this).val());
        });

        $("body").on("click", "#lastShowcaseSettings", function(){
            reloadShowcase($.param({"TourShowcaseStandardSettings": lastSettings}));
            setSettings(lastSettings);
        });

        $("body").on("click", "#defaultShowcaseSettings", function(){
            reloadShowcase($.param({"TourShowcaseStandardSettings": defaultSettings}));
            setSettings(defaultSettings);
        });

        // $("body").on("change", "#TourShowcaseStandardSettings_per_row, #TourShowcaseStandardSettings_open_tour_target, #TourShowcaseStandardSettings_rounding, #TourShowcaseStandardSettings_bg_color, #TourShowcaseStandardSettings_bg_block_color, #TourShowcaseStandardSettings_tour_link_color, #TourShowcaseStandardSettings_icon_color, #TourShowcaseStandardSettings_price_label_color, #TourShowcaseStandardSettings_price_color, #TourShowcaseStandardSettings_pagination_color", function(){
        //     $("#showcase-params", showcaseFrameDoc()).val($("#showcase-settings-form").serialize());
        // });

        $("iframe[name='xtrsmproduct']").load(function(){
            $.hideFade();
        });

    });
})(jQuery);