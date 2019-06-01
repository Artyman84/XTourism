/**
 * Created by Arty on 02.05.2015.
 */

;(function(undefined){

    var _host = "http://xtourism.localhost";
    //var _host = "http://diar.tech";

    var fader, style;

    function getFade(){
        if (!fader) {
            fader = document.createElement('div');
            fader.className = 'xtourism-ajax-loader';
            fader.innerHTML = '<div class="xtourism-spinner xtourism-big-spinner"></div><div class="xtourism-spinner-label">Загружаем туры</div>';
            document.body.appendChild(fader);
        }

        return fader;
    }

    function appendStyle(href){

        if (style) {
            document.body.removeChild(style);
        }

        style = document.createElement('link');
        style.setAttribute('href', _host + href);
        style.setAttribute('media', 'screen');
        style.setAttribute('rel', 'stylesheet');
        style.setAttribute('type', 'text/css');

        document.body.appendChild(style);
    }

    var scripts = document.getElementsByTagName("script");
    var container=null, params=null, script=null;

    for(var i=0, l=scripts.length; i<l; ++i){
        var src = scripts[i].getAttribute("src"), origin=_host + "/js/front_product/_.js?p=";

        if(src && src.indexOf(origin) != -1 ){
            params = src.split(origin);
            params = params[1];

            container = scripts[i].parentNode;
            script = scripts[i];
            break;
        }
    }

    if( params ){
        var iframe = document.createElement("iframe");
        iframe.id = "xtrsmproduct_" + Date.now() + Math.random();
        iframe.name = "xtrsmproduct";
        iframe.src = _host + "/index.php/FrontProduct/index/?p=" + params + "&if_id=" + iframe.id + "&cw=" + container.offsetWidth;
        iframe.frameBorder = "0";
        iframe.style.width = "100%";
        iframe.style.height = "0px";
        iframe.scrolling = "no";
        iframe.style.visibility = 'hidden';

        var init = function(event){
            if ( _host.indexOf(event.origin) === 0 ) {

                var data = JSON.parse(event.data);

                if (data && data.iid == iframe.id) {
                    
                    if( data.h !== undefined ) { // window height
                        iframe.style.height = data.h;
                        iframe.style.visibility = 'visible';
                    }

                    if (data.css !== undefined) {//color class
                        appendStyle(data.css);
                    }

                    if (data.cc !== undefined) {//color class
                        getFade().className = 'xtourism-ajax-loader ' + data.cc;
                    }

                    if (data.sm !== undefined) { //spinner message
                        getFade().querySelector('.xtourism-spinner-label').innerText = data.sm;
                    }

                    if( data.action !== undefined ) { // action

                        switch (data.action) {
                            case 'showFade': getFade().style.display = 'block'; break;
                            case 'hideFade': getFade().style.display = 'none'; break;
                            case 'scrollTo':
                                var scrollContainer = container;

                                while (scrollContainer && scrollContainer !== document.body) {
                                    var style = getComputedStyle(scrollContainer);

                                    if (["scroll", "auto", "visible"].indexOf(style.overflowY) !== -1) {
                                        break;
                                    }

                                    scrollContainer = scrollContainer.parentNode;
                                }

                                if(scrollContainer && scrollContainer !== document.body) {
                                    scrollContainer.scrollTo(0, data.top);
                                } else {
                                    window.scrollTo(0, data.top);
                                }

                                break;
                            case 'openPopup': window.open(_host + data.url, data.name, 'scrollbars=yes,width=' + data.width + ',height=' + window.innerHeight + ',left=' + (screen.width/2 - data.width/2)); break;
                        }

                    }

                }

            }
        };

        container.appendChild(iframe);
        window.addEventListener("message", init, false);
    }

    container.removeChild(script);
})();