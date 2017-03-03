/*For join page
 * Inclue two function, one is load new qr for each person, another is show rule popup
 * */
;(function(){
    var controller = function(){

    };
    //init
    controller.prototype.init = function(){
        var self = this;

        if(Common.getParameterByName('page') == 'form'){
            Common.gotoPin(1);
        }else{
            Common.gotoPin(0);
        }

        //Common.setParameterByName('page','test');
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
            //fill the form
            Api.getUserForm(function(data){
                if(data.status==1){
                    $('#input-name').val('123');
                    $('#input-mobile').val('123');
                    $('#input-address').val('123');
                }else{

                }
            });
            //location.search = Common.setParameterByName('page','form');
            Common.gotoPin(1);
        });

        //    submit form
        $('#form-contact .btn-submit').on('touchstart',function(){

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