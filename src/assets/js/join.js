/*For join page
* Inclue two function, one is load new qr for each person, another is show rule popup
* */
;(function(){

    $(document).ready(function(){
        //show rule popup
        $('.link-rule').on('touchstart', function(){
            $('.pop-rules').addClass('show');
            isScroll = true;
        });
        //close
        $('body').on('touchstart', '.btn-close',function(){
            $(this).parent().parent('.pop-rules').removeClass('show');
            isScroll = false;
        });

        //preload all images
        function loadImages(sources, callback) {
            var images = {};
            var loadedImages = 0;
            var numImages = 0;
            // get num of sources
            for(var src in sources) {
                numImages++;
            }
            for(var src in sources) {
                images[src] = new Image();
                images[src].onload = function() {
                    if(++loadedImages >= numImages) {
                        callback(images);
                    }
                };
                images[src].src = sources[src];
            }
        }

        var sources = {
            bag1: '/src/images/bag-1.jpg',
            bag2: '/src/images/bag-2.jpg',
            bag3: '/src/images/bag-3.jpg',
            text: '/src/images/text-overlay.png',
            smalllogo: '/src/images/small-logo.png',
            qr:$('#img2').attr('src')
        };

        //  load custom qrcode
        Common.msgBox('图片生成中...');
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');
        var image2 = document.getElementById('img2');

        //the image width is 750*610,relative window width is 100%
        var cWidth = window.innerWidth * 2,
            cHeight = parseInt(cWidth * 610 / 750 + 210 / 2 * cWidth / 750),
            img1Width = cWidth,
            img1Height = parseInt(cWidth * 610 / 750),
            img2Width = 210 * cWidth / 750,
            img3Width = 55 * cWidth / 750,
            img2Left = 480 * cWidth / 750,
            img3Left = 480 * cWidth / 750 + (img2Width - img3Width) / 2,
            img4Left = 110 * cWidth / 750,
            img4Width = 356 * cWidth / 750,
            img4Height = img4Width * 66/356;

        canvas.width = cWidth;
        canvas.height = cHeight;
        ctx.rect(0, 0, cWidth, cHeight);
        ctx.fillStyle = "#fff";
        ctx.fill();
        //ctx.imageSmoothingEnabled  = true;
        //the qrcode image is 210*210

        loadImages(sources, function(images) {
            var image1 = new Image(),
                image3 = new Image(),
                image4 = new Image();
            image1.src = '/src/images/bag-'+Math.round(Math.random() * (3 - 1) + 1)+'.jpg';
            image3.src = sources.smalllogo;
            image4.src = sources.text;
            ctx.drawImage(image1, 0,0,img1Width,img1Height);
            ctx.drawImage(image2, img2Left,img1Height-img2Width/2,img2Width,img2Width);
            ctx.drawImage(image3, img3Left,img1Height-img3Width/2,img3Width,img3Width);
            ctx.drawImage(image4, img4Left,img1Height+10,img4Width,img4Height);

            //    add custom text to canvas
            //var fsize = parseInt(24 * cWidth / 750) + 'px',
            //    fLeft = parseInt(460 * cWidth / 750);
            //ctx.fillStyle = "#000";
            //ctx.font = fsize+' lighter serif';
            //ctx.textAlign = 'end';
            //ctx.fillText('召集蜜友来助力，扫描积分赢人气',fLeft, img1Height+15);
            //ctx.fillText('Coach多重惊喜正在前方召唤',fLeft, img1Height+30);

            //    export canvas to one image by dataurl
            var dataURL = canvas.toDataURL('image/jpeg', 1.0);
            $('#result-img').attr('src',dataURL);
            $('.ajaxpop').remove();
        });









    });


})();
