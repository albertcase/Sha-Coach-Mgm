;(function(){

    var controller = function(){
    };
    //init
    controller.prototype.init = function(){
        var self = this;
        self.welcomePage();
    };
    //welcome page
    controller.prototype.welcomePage = function(){
        var self = this;
        Common.gotoPin(0);
        var mySwiper = new Swiper ('.swiper-container', {
            // Optional parameters
            loop: true,

            // If we need pagination
            pagination: '.swiper-pagination',

            // Navigation arrows
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',

            // And if we need scrollbar
            //scrollbar: '.swiper-scrollbar',
        });

        if(Common.getParameterByName('ishas') == 1){
            $('.btn-buy span').html('现已售罄');
        }
        $('.btn-buy').on('click',function(){
            _hmt.push(['_trackEvent', 'buttons', 'click', '即刻购买']);
            //    select product
            //    product name, product price
            Api.quota(function(data){
                if(data.status==1){
                    //    有库存，去订单页面
                    Common.goOrderPage();
                }else{
                    //    没有库存，更改按钮描述
                    _hmt.push(['_trackEvent', 'buttons', 'click', '现已售罄']);
                    $('.btn-buy span').html('现已售罄');
                }
            });

        });

        $('.gorosso').on('touchstart',function(){
            _hmt.push(['_trackEvent', 'link', 'click', '探索ROSSO VALENTINO系列']);
        });

    };

    //dom ready
    $(document).ready(function(){

        var welcome = new controller();
        welcome.init();


    });


})();

