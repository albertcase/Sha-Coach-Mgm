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
        Common.msgBox('图片生成中...');
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');
        var image2 = document.getElementById('img2');
        var image1 = new Image();
        image1.src = '/src/images/bag-'+Math.round(Math.random() * (3 - 1) + 1)+'.jpg';

        //the image width is 750*610,relative window width is 100%
        var cWidth = window.innerWidth,
            cHeight = parseInt(cWidth * 610 / 750 + 210 / 2 * cWidth / 750),
            img1Width = cWidth,
            img1Height = parseInt(cWidth * 610 / 750),
            img2Width = 210 * cWidth / 750,
            img2Left = 480 * cWidth / 750;

        canvas.width = cWidth;
        canvas.height = cHeight;
        //  add two images to canvas to merge
        image1.onload=function(){
            ctx.drawImage(image1, 0,0,img1Width,img1Height);
        };

        //the qrcode image is 210*210
        image2.onload=function(){

            ctx.drawImage(image2, img2Left,img1Height-img2Width/2,img2Width,img2Width);
            //    add custom text to canvas
            var fsize = parseInt(22 * cWidth / 750) + 'px',
                fLeft = parseInt(460 * cWidth / 750);
            ctx.font = fsize+' serif';
            ctx.textAlign = 'end';
            ctx.fillText('召集蜜友来助力，扫描积分赢人气',fLeft, img1Height+15);
            ctx.fillText('Coach多重惊喜正在前方召唤',fLeft, img1Height+30);

            //    export canvas to one image by dataurl
            var dataURL = canvas.toDataURL('image/jpg', 1.0);
            $('#result-img').attr('src',dataURL);
            $('.ajaxpop').remove();
        };



    });


})();
