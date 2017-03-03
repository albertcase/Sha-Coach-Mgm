/*For join page
* Inclue two function, one is load new qr for each person, another is show rule popup
* */
;(function(){

    $(document).ready(function(){
        //show rule popup
        $('.link-rule').on('touchstart', function(){
            $('.pop-rules').addClass('show');
        });
        //close
        $('body').on('touchstart', '.btn-close',function(){
            $(this).parent().parent('.pop-rules').removeClass('show');
        });


        //  load custom qrcode
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');
        var image1 = document.getElementById('img1');
        var image2 = document.getElementById('img2');
        canvas.width = 300;
        canvas.height = 300;
        //  add two images to canvas to merge
        image1.onload=function(){
            ctx.drawImage(image1, 0,0);
        };
        image2.onload=function(){

            ctx.drawImage(image2,  100,100,200,200);
            //    add custom text to canvas
            ctx.font = '48px serif';
            ctx.fillText('Hello world', 50, 100);

            //    export canvas to one image by dataurl
            var dataURL = canvas.toDataURL('image/jpeg', 1.0);
            $('#test').attr('src',dataURL);
        };



    });


})();
