/*For join page
 * Inclue two function, one is load new qr for each person, another is show rule popup
 * */
;(function(){
    var controller = function(){

    };
    //init
    controller.prototype.init = function(){
        var self = this;
        //load userinfo
        self.userInfo();

        //bind events
        self.bindEvent();
    };

    //bind Events
    controller.prototype.bindEvent = function(){
        var self = this;

        //show contact form
        $('.show-personal span').on('touchstart',function(){
            Common.gotoPin(1);
        });
    };

    //load user info and fill it
    controller.prototype.userInfo = function(){
        var self = this;
        Api.isLogin(function(data){
            var imgAvatar = data.avatar,
                score = data.score,
                scoreProgress = parseInt(data.score) / 520 * 100 + '%';
            $('.avatar img').attr('src',data.avatar);
            $('.stars .progress').css('width',scoreProgress);
            $('.total-score .num').html(score);
        });
    };


    $(document).ready(function(){
//    show form
        var exchange = new controller();
        exchange.init();

    });

})();