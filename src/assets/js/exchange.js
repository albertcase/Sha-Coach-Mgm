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
                scoreProgress = parseInt(score) / 520 * 100 + '%';
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