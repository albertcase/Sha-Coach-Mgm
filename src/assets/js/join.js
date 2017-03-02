/*For join page
* Inclue two function, one is load new qr for each person, another is show rule popup
* */
;(function(){



})();
$(document).ready(function(){
    //show rule popup
    $('.link-rule').on('touchstart', function(){
        $('.pop-rules').addClass('show');
    });
    //close
    $('body').on('touchstart', '.btn-close',function(){
        $(this).parent().parent('.pop-rules').removeClass('show');
    });

});