class AFormPopup {
    constructor(options) {
        var _this = this;

        _this.id = '_' + Math.random().toString(36).substr(2, 9);
        _this.options = options;
    }

    setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for(let i = 0; i < ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) == ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
    }

    init() {
        var _this = this;
        _this.remove();

        var div = document.createElement("div");
        div.innerHTML = `
            <div class="acelle-popup-cover"></div>
            <div class="acelle-popup-loader">
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
            </div>
            <div id="Popup`+_this.id+`" class="acelle-popup-container">
                
                <div class="acelle-popup-container-scroll">
                    <iframe id="Popup`+_this.id+`Frame" src="`+_this.options.url+`"></iframe>   
                    
                </div> 
                <div id="Popup`+_this.id+`Close" class="acelle-popup-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#000" d="M7.05022 7.05028C6.65969 7.4408 6.65969 8.07397 7.05022 8.46449L10.5858 12L7.05023 15.5356C6.6597 15.9261 6.6597 16.5593 7.05023 16.9498C7.44075 17.3403 8.07392 17.3403 8.46444 16.9498L12 13.4142L15.5355 16.9498C15.926 17.3403 16.5592 17.3403 16.9497 16.9498C17.3402 16.5592 17.3402 15.9261 16.9497 15.5356L13.4142 12L16.9497 8.46449C17.3402 8.07397 17.3402 7.4408 16.9497 7.05028C16.5592 6.65976 15.926 6.65976 15.5355 7.05028L12 10.5858L8.46443 7.05028C8.07391 6.65975 7.44074 6.65975 7.05022 7.05028Z"/></svg>
                </div>
            </div>`;
        div.classList.add("acelle-popup");
        div.id = 'Popup_' + _this.id;
        document.body.appendChild(div);

        this.node = div;
        this.iframe = document.getElementById('Popup'+_this.id+'Frame');

        this.loadCss('{{ url('core/css/form_popup.css') }}');

        window.addEventListener("message", function(event) {
            if (typeof(event.data.frameSize) != 'undefined') {
                _this.adjustIframeSize(event.data.frameSize);
            }

            if (typeof(event.data.alert) != 'undefined') {
                alert(event.data.alert.message);
            }

            if (typeof(event.data.loaded) != 'undefined') {
                var es = document.getElementsByClassName('acelle-popup');
                if(es.length > 0){
                    es[0].classList.add("acelle-popup-loaded");
                }
            }
        });

        document.getElementById('Popup'+_this.id+'Close').addEventListener("click", function() {
            _this.hide();
        });

        // set opacity
        _this.setOverlayOpacity();
    }

    setOverlayOpacity() {
        if (this.options.overlayOpacity) {
            var es = document.getElementsByClassName('acelle-popup');
            if(es.length > 0){
                es[0].style.background = 'rgba(0,0,0,'+this.options.overlayOpacity+')';
            }
        }
    }

    remove() {
        var oldEs = document.getElementsByClassName('acelle-popup');
        if(oldEs.length > 0){
            oldEs[0].parentNode.removeChild(oldEs[0]);
        }
    }

    adjustIframeSize(size) {
        this.iframe.style.height = size.height + 'px';        
    }

    loadCss(url) {
        if (window.form_popup_css == null) {
            var head  = document.getElementsByTagName('head')[0];
            var link  = document.createElement('link');
            link.rel  = 'stylesheet';
            link.type = 'text/css';
            link.href = url;
            link.media = 'all';
            head.appendChild(link);

            window.form_popup_css = link;
        }
    }

    show() {
        document.body.classList.add("acelle-popup-open");
    }

    hide() {
        document.body.classList.remove("acelle-popup-open");
    }

    loadOneTime() {
        if (this.getCookie('popupLoaded') === "") {
            this.init();
            this.show();

            this.setCookie('popupLoaded', true);
        }        
    }
    
    load(options) {
        // update options            
        if (typeof(options) !== 'undefined') {
            this.options = $.extend({}, this.options, options);
        }

        this.init();
        this.show();
    }
};