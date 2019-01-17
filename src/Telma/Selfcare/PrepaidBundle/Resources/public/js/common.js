$(document).ready(function () {
    if ($('#matriceoffre_bundle').length > 0) {
        $("#matriceoffre_bundle").selectbox();
    }
    if ($('.error').length > 0) {
        $('.error').fancybox();
    }
    if ($('.error1').length > 0) {
        $('.error1').fancybox();
    }
    var addItem = function(){
        if(window.innerWidth < 768){
            $('.abt').addClass('mobile-abt');
        }
        else{
            $('.abt').removeClass('mobile-abt');
        }
    }
    addItem();
    $(window).resize(function(){
        addItem();
    });
});

// Minified version of isMobile included in the HTML since it's <1kb
(function (i) {
    var e = /iPhone/i, n = /iPod/i, o = /iPad/i, t = /(?=.*\bAndroid\b)(?=.*\bMobile\b)/i, r = /Android/i, d = /BlackBerry/i, s = /Opera Mini/i, a = /IEMobile/i, b = /(?=.*\bFirefox\b)(?=.*\bMobile\b)/i, h = RegExp("(?:Nexus 7|BNTV250|Kindle Fire|Silk|GT-P1000)", "i"), c = function (i, e) {
        return i.test(e)
    }, l = function (i) {
        var l = i || navigator.userAgent;
        this.apple = {
            phone: c(e, l),
            ipod: c(n, l),
            tablet: c(o, l),
            device: c(e, l) || c(n, l) || c(o, l)
        }, this.android = {
            phone: c(t, l),
            tablet: !c(t, l) && c(r, l),
            device: c(t, l) || c(r, l)
        }, this.other = {
            blackberry: c(d, l),
            opera: c(s, l),
            windows: c(a, l),
            firefox: c(b, l),
            device: c(d, l) || c(s, l) || c(a, l) || c(b, l)
        }, this.seven_inch = c(h, l), this.any = this.apple.device || this.android.device || this.other.device || this.seven_inch
    }, v = i.isMobile = new l;
    v.Class = l
})(window);
// My own arbitrary use of isMobile, as an example
(function () {
    if (isMobile.apple.phone || isMobile.android.phone || isMobile.seven_inch) {
        document.write("<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>");
    }
})();
function verifieNumeric(event){
    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 46
     || event.keyCode == 37 || event.keyCode == 39) {
        return true;
    }
    else if ( key < 48 || key > 57 ) {
        return false;
    }
    else return true;
}