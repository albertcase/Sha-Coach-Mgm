/*All the api collection*/
Api = {
    //是否授权，并且获取用户信息
    isLogin:function(callback){
        //Common.msgBox('loading...');
        //$.ajax({
        //    url:'/api/quota',
        //    type:'POST',
        //    dataType:'json',
        //    success:function(data){
        //        $('.ajaxpop').remove();
        //        return callback(data);
        //        //status=1 有库存
        //    }
        //});

        return callback({
            status:1,
            avatar:'/src/images/qr-1.png',
            score:'100'
        })


    },

    //保存用户订单信息
    //sex  name  mobile email province city address
    order:function(obj,callback){
        Common.msgBox('loading...');
        $.ajax({
            url:'/api/order',
            type:'POST',
            dataType:'json',
            data:obj,
            success:function(data){
                $('.ajaxpop').remove();
                return callback(data);
            }
        });


    },
    //预约到店
    //sex  name  mobile  province city store month day time
    reservation:function(obj,callback){
        Common.msgBox('loading...');
        $.ajax({
            url:'/api/submit',
            type:'POST',
            dataType:'json',
            data:obj,
            success:function(data){
                $('.ajaxpop').remove();
                return callback(data);
            }
        });
    },



};