/**
 * Created with JetBrains PhpStorm.
 * User: Arti
 * Date: 07.07.14
 * Time: 18:57
 * To change this template use File | Settings | File Templates.
 */

;(function($, undefined){

    $(function(){
        $("body").on("click", "div#hp_id img", function(){
            if( $("div#chp_id img").attr("src") != $(this).attr("src") ) {

                $("div#hp_id img").css({"opacity": 1, "border-color": "#ddd" });

                var $img = $(this);
                var src = $(this).attr("src");

                $("div#chp_id img").hide(1, function () {
                    $(this).attr("src", src);
                    $(this).show();
                    $img.css({"opacity": 0.4, "border-color": "#337ab7" });
                });
            }
        });

        $("body").on("mouseover", "div#hp_id img", function(){
            $(this).css({"opacity": 0.4, "border-color": "#337ab7" });
        })

        $("body").on("mouseout", "div#hp_id img", function(){
            if( $("div#chp_id img").attr("src") != $(this).attr("src") ){
                $(this).css({"opacity": 1, "border-color": "#ddd" });$(this).css("opacity", 1);
            }
        })
    });
})(jQuery);