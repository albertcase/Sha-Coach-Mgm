;(function(){

    var controller = function(){
    };
    //init
    controller.prototype.init = function(){
        var self = this;
        self.verifyOrder();
    };

    //确认订单
    controller.prototype.verifyOrder = function(obj){
        var self = this;
        Common.gotoPin(0);
        $('.btn-back').on('touchstart',function(){
            _hmt.push(['_trackEvent', 'buttons', 'click', '返回修改']);
            Common.goHomePage();
        });

        $('#pin-pay-success .btn').on('touchstart',function(){
            _hmt.push(['_trackEvent', 'link', 'click', '探索ROSSO VALENTINO系列']);
        });

        //var orderInfo = self.orderInfo;
        //$('#order-name').html(orderInfo.name);
        //$('#order-phone').html(orderInfo.mobile);
        //$('#order-mail').html(orderInfo.email);
        //$('#order-address').html(orderInfo.province+orderInfo.city+orderInfo.address);
        //
        ////确认订单，开始支付请求
        //$('.btn-submit-order').on('touchstart',function(){
        //
        //});

    };


    //dom ready
    $(document).ready(function(){

        var valentino = new controller();
        valentino.init();


    });


})();

