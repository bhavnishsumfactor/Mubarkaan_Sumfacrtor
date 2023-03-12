<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: Object.keys(JSON.parse(languages)).join(','),
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');        
    }
    function setLangCookie(langcode){
        let tempLang = '/en/' + langcode;
        if (langcode == 'en') {
            tempLang = '/auto/en';
        }
        $.removeCookie('googtrans',{path:'/', domain: domain});
        $.removeCookie('googtrans',{path:'/', domain: '.' + domain});
        $.cookie('googtrans', tempLang, {path:'/', expires: 15}); 
    }    
    setLangCookie(pageDefaultLanguage);
</script>
<script  src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>
$(document).ready(function(){
    if (window.location.href.indexOf('?language') > -1) {
        setTimeout(function(){
            history.pushState('', document.title, window.location.pathname);
        },500);
    }
}); 
</script>