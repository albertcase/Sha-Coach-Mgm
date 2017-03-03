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

    //获取用户表单信息
    //name mobile address
    getUserForm:function(callback){
        //Common.msgBox('loading...');
        //$.ajax({
        //    url:'/api/order',
        //    type:'POST',
        //    dataType:'json',
        //    data:obj,
        //    success:function(data){
        //        $('.ajaxpop').remove();
        //        return callback(data);
        //    }
        //});

        return callback({
            status:1,
            avatar:'/src/images/qr-1.png',
            score:'100'
        });


    },
    submitUserForm:function(obj,callback){
        //Common.msgBox('loading...');
        //$.ajax({
        //    url:'/api/order',
        //    type:'POST',
        //    dataType:'json',
        //    data:obj,
        //    success:function(data){
        //        $('.ajaxpop').remove();
        //        return callback(data);
        //    }
        //});

        return callback({
            status:1,
            avatar:'/src/images/qr-1.png',
            score:'100'
        });


    },



};