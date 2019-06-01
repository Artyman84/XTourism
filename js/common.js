/**
 * Created with JetBrains PhpStorm.
 * User: Arti
 * Date: 26.08.14
 * Time: 16:51
 * To change this template use File | Settings | File Templates.
 */

;(function($, undefined){

    $.parseURL = function(url) {
        var a =  document.createElement('a');
        a.href = url;
        return {
            source: url,
            protocol: a.protocol.replace(':',''),
            host: a.hostname,
            port: a.port,
            query: a.search,
            script: (function(){
                if( url.indexOf("admin_diar_1017.php") != -1 ){
                    return "admin_diar_1017.php";
                } else {
                    return "index.php";
                }
            })(),
            params: (function(){
                var ret = {},
                    seg = a.search.replace(/^\?/,'').split('&'),
                    len = seg.length, i = 0, s;
                for (;i<len;i++) {
                    if (!seg[i]) { continue; }
                    s = seg[i].split('=');
                    ret[decodeURIComponent(s[0])] = decodeURIComponent(s[1]);
                }
                return ret;
            })(),
            file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],
            hash: a.hash.replace('#',''),
            path: a.pathname.replace(/^([^\/])/,'/$1'),
            relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],
            segments: a.pathname.replace(/^\//,'').split('/')
        };
    };

    $.baseUrl = function(){
        var urlData = $.parseURL(document.location.href);
        var segments = '/';

        for(var i= 0, l=urlData.segments.length; i<l; i++){
            segments += urlData.segments[i] + "/";
            if( urlData.segments[i] == urlData.script ) {
                break;
            }
        }

        return  urlData.protocol + "://" + urlData.host + segments;
    };

    $.basePath = function(){
        var urlData = $.parseURL(document.location.href);
        var segments = '/';

        for(var i= 0, l=urlData.segments.length; i<l; i++){
            if( urlData.segments[i] == urlData.script ) {
                break;
            }
            segments += urlData.segments[i] + "/";
        }

        return  urlData.protocol + "://" + urlData.host + segments;
    };

    $.showFade = function(delay){

        if( !$.toInt($("body div.ajax-loader").length) ){
            $("body").append("<div class='ajax-loader'></div>");
        }

        if( !$( "body div.ajax-loader").is(":visible") ) {
            $("body div.ajax-loader").fadeIn(delay !== undefined ? delay : "show");
        }
    }

    $.hideFade = function(){
        if( $.toInt($("body div.ajax-loader").length) ){
            $( "body div.ajax-loader").fadeOut("hide", function(){$(this).remove();});
        }
    }

    $.createUrl = function(ca, params) {
        var _params = "";

        if( typeof params == "object" ){
            for(var k in params ){
                _params += (_params ? "&" : "?") + k + "=" + params[k];
            }
        }

        return $.baseUrl() + ca + _params;
    }

    $.sendRequest = function(url, data, __callbackSuccess, dataType, fade, isFormData){

        var fade = fade === undefined ? true : fade;
        var ajaxData = {
            type: "POST",
            dataType: typeof dataType == "string" ? dataType : "JSON",
            cache: false,
            url: typeof url == "object" ? url.url : $.createUrl(url),
            data: data,
            success: function(json){

                if( fade ) $.hideFade();

                if( typeof __callbackSuccess == "function" ){
                    __callbackSuccess(json);
                }
            }
        };

        if( isFormData !== undefined && isFormData ){
            ajaxData.processData = false;
            ajaxData.contentType = false;
        }

        if( fade ) $.showFade();

        $.ajax(ajaxData);
    };

    $.navigate = function (href, newTab) {
        var a = document.createElement('a');
        a.href = href;
        if (newTab) {
            a.setAttribute('target', '_blank');
        }
        a.click();
    }

    $.endingNumberType = function(number){
        number = number.toString();
        var length = number.length;

        if( number[length-1] == "1" && number != "11" ){

            return 1;

        } else if( $.inArray(number[length-1], ["2", "3", "4"]) != -1 && $.inArray(number, ["12", "13", "14"]) ){

            return 2;

        } else {

            return 3;
        }

    }

    $.toInt = function(v){

        switch ( typeof v){
            case "boolean":
                return v ? 1 : 0;

            case "string":
                var n = "";

                for( var i=0; i < v.length; ++i ){
                    if( !isNaN( parseInt(v[i]) ) ){
                        n += v[i];
                    }
                }

                return n ? parseInt(n) : 0;

            case "number":
                return v;

            case undefined:
            default :
                return 0;
        }
    }

    $.arrayValues = function(input){
        var output = [];

        for( var key in input ){
            output[output.length] = input[key];
        }

        return output;
    }

    $.objectKeys = function(obj){
        var keys = [],
            k;
        for (k in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, k)) {
                keys.push(k);
            }
        }
        return keys;
    };

    $.escapeHtml = function (text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    $.isset = function (object){
        return (typeof object !=='undefined');
    }

    $.reCountElements = function($tbody, selClass){
        var selClass = selClass === undefined ? "t-countElement" : selClass;

        $tbody.find("tr td." + selClass).each(function(i){
            $(this).html(i+1);
        });
    }

    $.getFrameDocument = function(frameName){
        var doc = null, frame = frames[frameName];

        if( frame !== undefined ){
            if (frame.contentDocument) { // FF Chrome

                doc = frame.contentDocument;

            } else if ( frame.contentWindow ) { // IE

                doc = frame.contentWindow.document;

            } else if( frame.document ){

                doc = frame.document;
            }
        }

        return doc;
    }

    $.uniqid = function(pr, en){
        var pr = pr || '', en = en || false, result, us;

        this.seed = function (s, w) {
            s = parseInt(s, 10).toString(16);
            return w < s.length ? s.slice(s.length - w) :
                (w > s.length) ? new Array(1 + (w - s.length)).join('0') + s : s;
        };

        result = pr + this.seed(parseInt(new Date().getTime() / 1000, 10), 8)
            + this.seed(Math.floor(Math.random() * 0x75bcd15) + 1, 5);

        if (en) result += (Math.random() * 10).toFixed(8).toString();

        return result;
    }

    var checkboxGroup = function(wrapperID, __callCheck, __callUnCheck){
        var $wrap = $("#" + wrapperID);

        if( !$wrap.length ){
            return;
        }

        var callback = function($input, __){
            if( typeof __ == "function"){
                __($input);
            }
        }

        $("body").on("change", "#" + wrapperID + " input.ch-parent", function(){

            if( $(this).is(":checked") ){
                var $span = $wrap.find("input.ch-child").parent().find("span.glyphicon-unchecked");
                $span.removeClass("glyphicon-unchecked").addClass("glyphicon-check text-info");
                $span.parent().find("input").each(function(){
                    $(this).prop("checked", true);
                    callback($(this), __callCheck);
                });

            } else {
                var $span = $wrap.find("input.ch-child").parent().find("span.glyphicon-check");
                $span.removeClass("glyphicon-check text-info").addClass("glyphicon-unchecked");
                $span.parent().find("input").each(function(){
                    $(this).prop("checked", false);
                    callback($(this), __callUnCheck);
                });
            }

        });

        $("body").on("change", "#" + wrapperID + " input.ch-child", function(){
            var total = $wrap.find("input.ch-child").length;
            var checked = $wrap.find("input.ch-child:checked").length;

            if( $(this).is(":checked") && total == checked ){
                var $span = $wrap.find("input.ch-parent").parent().find("span");
                $span.removeClass("glyphicon-unchecked").addClass("glyphicon-check text-info");
                $span.parent().find("input").prop("checked", "checked");

            } else if( !$(this).is(":checked") && checked < total ){
                var $span = $wrap.find("input.ch-parent").parent().find("span.glyphicon-check");
                $span.removeClass("glyphicon-check text-info").addClass("glyphicon-unchecked");
                $span.parent().find("input").prop("checked", false);
            }

            if($(this).is(":checked")) {
                callback($(this), __callCheck);
            } else {
                callback($(this), __callUnCheck);
            }

        });
    };

    $.blinkHash = function(prefix, url){
        $(function(){
            if( document.location.hash && document.location.hash.search('#blink=') != -1){
                var id = document.location.hash.replace('#blink=', '');

                if( url === undefined){
                    var url_partials = document.location.href.split("#blink=");
                    url = url_partials[0];
                }

                window.history.replaceState(null, null, url);
                $.blinkElement("#" + prefix + id);
            }
        })
    }

    $.blinkElement = function(sel){
        var $sel = $(sel);
        $sel.addClass('warning').fadeTo('slow', 0.4, function(){
            $sel.fadeTo('slow', 1, function(){$sel.removeClass("warning");});
        });
    }

    $.initCheckboxGroup = function(wrapID, __check, __unCheck) {
        if( typeof wrapID != "object"){
            wrapID = [wrapID];
        }

        for( var i in wrapID ) {
            checkboxGroup(wrapID[i], __check, __unCheck);
        }
    }

    $.bindPopover = function(){

        $("body").on("click", "a[data-toggle=\'popover\']", function(){
            $(this).popover({html: true});
            $(this).popover("toggle");
            return false;
        });

        $("body").on("hidden.bs.popover", "a[data-toggle=\'popover\']", function(){
            $(this).popover("destroy");
            return false;
        });

    }

    $.bindDatepickerEraser = function(){
        $("table.table-filter tr.filters td .t-filter-datepicker").each(function(){
            if( !$(this).parent().find("span").length ) {

                $(this).parent().addClass("text-nowrap");
                var $erase = $('<span class="glyphicon glyphicon-erase" data-toggle="tooltip" title="Очистить" style="position: relative; left: -22px; cursor: pointer; top: 2px;"></span>');
                $erase.click(function () {
                    var old_date = $(this).prev().val();
                    $(this).prev().val("");
                    if (old_date) {
                        $(this).prev().trigger("change");
                    }
                });
                $(this).after($erase);
            }
        });
    }



    /***************************** Need for changing paddings for modal dialogs  *****************************/
    $.changeModalPaddings = function(){
        if($.fn.modal !== undefined) {
            var fixedCls = '.navbar-fixed-top,.navbar-fixed-bottom';
            var oldSSB = $.fn.modal.Constructor.prototype.setScrollbar;
            $.fn.modal.Constructor.prototype.setScrollbar = function () {
                oldSSB.apply(this);
                if (this.bodyIsOverflowing && this.scrollbarWidth)
                    $(fixedCls).css('padding-right', this.scrollbarWidth);
            };

            var oldRSB = $.fn.modal.Constructor.prototype.resetScrollbar;
            $.fn.modal.Constructor.prototype.resetScrollbar = function () {
                oldRSB.apply(this);
                $(fixedCls).css('padding-right', '');
            };
        }
    }
    /*********************************************************************************************************/




    /************************** ACTIONS **************************/
    $(function(){

        /***************************** Checkboxes **************/
        $("body").on("click", "div.xtourism-checkbox span.glyphicon-unchecked", function(){
            $(this).removeClass("glyphicon-unchecked").addClass("glyphicon-check text-info");
            $(this).parent().find("input:checkbox").prop("checked", "checked").trigger("change");
        });

        $("body").on("click", "div.xtourism-checkbox span.glyphicon-check", function(){
            $(this).removeClass("glyphicon-check text-info").addClass("glyphicon-unchecked");
            $(this).parent().find("input:checkbox").prop("checked", false).trigger("change");
        });


        $("body").on("click", "div.breadcrumb a", function(){
            $.showFade();
        });

    });


})(window.jQuery);