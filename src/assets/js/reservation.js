;(function(){

    var controller = function(){
    };
    //init
    controller.prototype.init = function(){
        var self = this;
        self.orderForm();
    };

    //fill the order information
    controller.prototype.orderForm = function(){
        var self = this;
        Common.gotoPin(0);
        //$('#form-contact input').on('keyup',function(){
        //    self.validateForm();
        //});
        //
        //$('#form-contact select').on('change',function(){
        //    self.validateForm();
        //});
        //submit the reservation
        $('#form-contact .btn-submit span').on('touchstart', function(){
            _hmt.push(['_trackEvent', 'btn', 'click', '预约完成']);
            if(self.validateForm()){
                if(!$('#input-receive').is(':checked')){
                    alert('请接受隐私条款方能提交');
                    return;
                }
                //console.log('通过前端验证，可以提交');
                //sex  name  mobile email province city address
                var sex = document.getElementById('input-title').value,
                    name = document.getElementById('input-name').value,
                    mobile = document.getElementById('input-mobile').value,
                    email = document.getElementById('input-mail').value;

                var orderInfo = {
                    sex:sex,
                    name:name,
                    mobile:mobile,
                    email:email
                };
                Api.reservation(orderInfo,function(data){
                    if(data.status==1){
                        //    提交成功，去提示预约成功页面
                        Common.gotoPin(1);
                    }else{
                        alert(data.msg);
                    }
                })

            }
        });

        $('#pin-pay-success .btn').on('touchstart',function(){
            _hmt.push(['_trackEvent', 'link', 'click', '探索ROSSO VALENTINO系列']);
        });

    };


    controller.prototype.validateForm = function(){
        var self = this;
        var validate = true,
            inputTitle = document.getElementById('input-title'),
            inputName = document.getElementById('input-name'),
            inputMobile = document.getElementById('input-mobile'),
            inputMail = document.getElementById('input-mail'),
            inputCheck = $('#input-receive');

        if(!inputTitle.value || (inputTitle.value=="称谓")){
            Common.errorMsg.add(inputTitle.parentElement,'请选择合适的称谓');
            validate = false;
        }else{
            Common.errorMsg.remove(inputTitle.parentElement);
        };

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

        if(!inputMail.value){
            Common.errorMsg.add(inputMail.parentElement,'邮箱不能为空');
            validate = false;
        }else{
            var regMail=/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
            if(!(regMail.test(inputMail.value))){
                validate = false;
                Common.errorMsg.add(inputMail.parentElement,'邮箱格式错误，请重新输入');
            }else{
                Common.errorMsg.remove(inputMail.parentElement);
            }
        }


        //if(!inputCheck.is(':checked')){
        //    validate = false;
        //    Common.errorMsg.add(inputCheck[0].parentElement,'请接受隐私条款');
        //}else{
        //    Common.errorMsg.remove(inputCheck[0].parentElement);
        //}


        if(validate){
            return true;
        }
        return false;
    };



    //dom ready
    $(document).ready(function(){

        var valentino = new controller();
        valentino.init();


    });


})();

