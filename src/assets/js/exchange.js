/*For join page
 * Inclue two function, one is load new qr for each person, another is show rule popup
 * */
;(function(){
    isScroll = true;
    var controller = function(){
        this.hasInfo = false;
        this.enableCopon = false;
    };
    //init
    controller.prototype.init = function(){
        var self = this;

        //location.hash = '#1';
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
        $('.product-lists').on('click', '.btn-buy', function(){
            var curIndex = $(this).parent().index();
            //location.hash = '#2';
            //check if the user has chance
            var id = $(this).attr('pid');
            var productObj = {
                id:id,
                img:$(this).parent().find('.p-img').html(),
                title:$(this).parent().find('.p-title').html(),
                price:$(this).parent().find('.p-price').find('.num').html(),
                number:$(this).parent().find('.p-number').find('.num').html()
            };
            Api.isCheck(function(data){
                if(data.status==1){
                    self.showProductDetails(productObj);
                }else{
                    Common.alertBox.add('你已经成功完成<br>两次兑换任务');
                }

            });

        });

        //exchange the product
        $('#product-details-page').on('touchstart', '.btn-buy', function(){
            //check if the user has chance
            //do something
            var id = $(this).attr('pid');
            if(id==1){
            //    coupon
                self.enableCopon = true;
            }else{
                self.enableCopon = false;
            };
            //else prize
            Api.isAvaliable({
                id:id
            },function(result){
                if(result.status==1){
                    //    可以兑换
                    if(self.hasInfo){
                    //    go list page
                    //    Common.gotoPin(0);
                        if(self.enableCopon){
                            self.enableCopon = false;
                            self.addCoupon();
                        }else{
                            Common.alertBox.add('兑换成功');
                            //location.reload();
                        }
                    }else{
                        //go form to fill
                        Common.gotoPin(1);
                    };
                }else{
                    Common.alertBox.add(result.msg);
                }
            });
        });

        //show contact form
        $('.show-personal span').on('touchstart',function(){
            //fill the form
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
                        if(self.enableCopon){
                            self.enableCopon = false;
                            self.addCoupon();
                        }else{
                            Common.gotoPin(2);
                            Common.alertBox.add('提交成功');
                        }
                    }else{
                        Common.alertBox.add(data.msg);
                    }
                });
            }
        });

    };

    controller.prototype.addCoupon = function(){
        var i = 1;
        Api.coupon(function(data){
            if(data.status){
                var cardListJSON = data.msg;
                wx.addCard({
                    cardList: [{
                        cardId: cardListJSON[i-1].cardId,
                        cardExt: '{"timestamp":"'+cardListJSON[i-1].cardExt.timestamp+'","signature":"'+cardListJSON[i-1].cardExt.signature+'"}'
                    }],

                    success: function(res) {
                        var cardList = res.cardList;
                        //alert(JSON.stringfiy(res));
                    },
                    fail: function(res) {
                        //alert(JSON.stringfiy(res));
                    },
                    complete: function(res) {
                        //alert(JSON.stringfiy(res));
                        location.reload();
                    },
                    cancel: function(res) {
                        //alert(JSON.stringfiy(res));
                        location.reload();
                    },
                    trigger: function(res) {
                        //alert(JSON.stringfiy(res));
                    }
                });
            }else{
                Common.alertBox.add(data.msg);
            }

        });
    };

    //load user info and fill it
    controller.prototype.userInfo = function(){
        var self = this;
        Api.isLogin(function(data){
            if(data.status==1){
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
                    self.hasInfo = true;
                }
            }else{
                Common.alertBox.add(data.msg);
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
                            '需要<span class="num">'+data.msg[i].score+'</span>积分'+
                            '</div>'+
                            '<div class="p-number">'+
                            '剩余<span class="num">'+data.msg[i].quota+'</span>件'+
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

    //product details
    controller.prototype.showProductDetails = function(obj){
        var self = this;
        //update html
        //obj include img, title, price, number
        Common.gotoPin(2);
        var pwHtml = '<div class="product-wrapper">'+
            '<div class="p-img">'+obj.img+
            '</div>'+
            '<div class="p-title">'+obj.title+
            '</div>'+
            '</div>'+
            '<div class="p-price">'+
            '兑换需要'+obj.price+'积分'+
            '</div>'+
            '<div class="p-number">'+
            '抓紧了，此宝贝仅剩余'+obj.number+'件'+
            '</div>'+
            '<div class="btn btn-buy" pid="'+obj.id+'">'+
            '确认兑换'+
            '</div>';

        $('.pw').html(pwHtml);
    };


    $(document).ready(function(){
//    show form
        var exchange = new controller();
        exchange.init();

        $('body').on('touchstart','.btn-alert-ok',function(){
            if($('body').hasClass('page-exchange') && $('#product-details-page').hasClass('current')){
                //location.reload();
                window.location.href = window.location.href+'?t='+new Date().getTime();
            }
        });

    });

})();