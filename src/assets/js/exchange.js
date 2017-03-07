/*For join page
 * Inclue two function, one is load new qr for each person, another is show rule popup
 * */
;(function(){
    var controller = function(){

    };
    //init
    controller.prototype.init = function(){
        var self = this;

        if(location.hash=='#form'){
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

        //exchange the product
        $('.product-lists').on('touchstart', '.btn-buy', function(){
            Api.isCheck({
              id:$(this).attr('pid')
            },function(data){
                console.log(data);
                if(data.status==1){
                    //do something

                }else{
                    Common.alertBox.add(data.msg);
                }

            });
        });

        //show contact form
        $('.show-personal span').on('touchstart',function(){
            //fill the form
            //Api.getUserForm(function(data){
            //    if(data.status==1){
            //        $('#input-name').val('123');
            //        $('#input-mobile').val('123');
            //        $('#input-address').val('123');
            //    }else{
            //
            //    }
            //});
            location.hash = '#form';
            //location.search = Common.setParameterByName('page','form');
            Common.gotoPin(1);
        });

        //    submit form
        $('#form-contact .btn-submit').on('touchstart',function(){
            if(self.validateForm()){
                var name =$('#input-name').val(),
                    mobile =$('#input-mobile').val(),
                    address =$('#input-address').val();
                Api.submitUserForm({
                    name:name,
                    cellphone:mobile,
                    address:address
                },function(data){
                    if(data.status==1){
                        console.log('login success,go page1');
                        location.hash = '#exchange';
                        Common.gotoPin(0);
                    }else{
                        Common.alertBox.add(data.msg);
                    }
                });
            }
        });

    };

    //load user info and fill it
    controller.prototype.userInfo = function(){
        var self = this;
        Api.isLogin(function(data){
            var imgAvatar = data.msg.headimgurl,
                score = data.msg.score,
                maxscore = data.msg.maxscore;
            var scoreProgress =0;
            if(maxscore>100 && maxscore<5000){
                //    star num is 1
                scoreProgress = '33.3%';
                $('.p1-t1').html('再接再励，召集蜜友来助力');
            } else if(maxscore>=5000 && maxscore<10000){
                //    star num is 2
                scoreProgress = '66.6%';
                $('.p1-t1').html('下一位超人气天后就是你');
            }else if(maxscore>=10000){
                //    star num is 3
                scoreProgress = '100%';
                $('.p1-t1').html('积分爆表，缤纷好礼都归你');
            }else{
                //    star num is 0
                scoreProgress = '0';
                $('.p1-t1').html('行动起来，姐妹淘都在等你的邀请');
            }
            $('.avatar img').attr('src',imgAvatar);
            $('.stars .progress').css('width',scoreProgress);
            $('.total-score .num').html(score);

            var info = data.info;
            if(info){
                //    user info
                $('#input-name').val(info.name);
                $('#input-mobile').val(info.cellphone);
                $('#input-address').val(info.address);
            }

        });

        //get all prize list
        Api.prizeList(function(data){

            if(data.status==1){
                if(data.msg.length>0){
                    var pList = '';
                    for(var i=0;i<data.msg.length;i++){
                        pList = pList + '<li class="item">'+
                            '<div class="p-img">'+
                            '<img src="'+data.msg[i].image+'" alt=""/>'+
                            '</div>'+
                            '<div class="p-title">'+data.msg[i].name+
                            '</div>'+
                            '<div class="p-price">'+
                            '需要'+data.msg[i].score+'积分'+
                            '</div>'+
                            '<div class="p-number">'+
                            '剩余'+data.msg[i].quota+'件'+
                            '</div>'+
                            '<div class="btn btn-buy" pid="'+data.msg[i].id+'">'+
                            '兑 换'+
                            '</div>'+
                            '</li>';
                    };
                    $('#prize-lists').html(pList);
                }else{
                    Common.alertBox.add('暂时没有奖品');
                }
            }else{
                Common.alertBox.add(data.msg);
            }
        });
    };

    //validation the form
    controller.prototype.validateForm = function(){
        var self = this;
        var validate = true,
            inputName = document.getElementById('input-name'),
            inputMobile = document.getElementById('input-mobile'),
            inputAddress = document.getElementById('input-address');

        if(!inputName.value){
            Common.errorMsg.add(inputName.parentElement,'请填写姓名');
            validate = false;
        }else{
            Common.errorMsg.remove(inputName.parentElement);
        };

        if(!inputMobile.value){
            Common.errorMsg.add(inputMobile.parentElement,'手机号码不能为空');
            validate = false;
        }else{
            var reg=/^1\d{10}$/;
            if(!(reg.test(inputMobile.value))){
                validate = false;
                Common.errorMsg.add(inputMobile.parentElement,'手机号格式错误，请重新输入');
            }else{
                Common.errorMsg.remove(inputMobile.parentElement);
            }
        }

        if(!inputAddress.value){
            Common.errorMsg.add(inputAddress.parentElement,'请填写地址');
            validate = false;
        }else{
            Common.errorMsg.remove(inputAddress.parentElement);
        };

        if(validate){
            return true;
        }
        return false;
    };


    $(document).ready(function(){
//    show form
        var exchange = new controller();
        exchange.init();

    });

})();