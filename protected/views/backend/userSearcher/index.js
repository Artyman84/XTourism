/**
 * Created by Arty on 13.02.2016.
 */

(function($, undefined){

    $(function(){

        $('.search-button').click(function(){

            if( $('.search-form').is(':hidden') ){
                $(this).find('span:first').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
            } else {
                $(this).find('span:first').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
            }

            $('.search-form').toggle();
            return false;
        });

        $('.search-form form').submit(function(){
            $('#users_searcher_grid_view').yiiGridView('update', {
                data: $(this).serialize()
            });
            return false;
        });

        $("body").on("click", "a.t-searcherSettings", function(){
            $.showFade();
            window.location.href = $.createUrl("UserSearcher/Settings", {"user_id": $(this).closest("tr").attr("user_id")});
        });

        $("body").on("click", "a.t-searcherValues", function(){
            $.showFade();
            window.location.href = $.createUrl("UserSearcher/Values", {"user_id": $(this).closest("tr").attr("user_id")});
        });

        $("body").on("click", "a.t-searcherFilters", function(){
            $.showFade();
            window.location.href = $.createUrl("UserSearcher/Filters", {"user_id": $(this).closest("tr").attr("user_id")});
        });

    });

})(jQuery);