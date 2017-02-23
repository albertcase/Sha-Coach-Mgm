;(function(){
    wx.ready(function(){
        //wx.hideOptionMenu({
        //    menuList: ["menuItem:share:appMessage","menuItem:share:timeline","menuItem:share:qq","menuItem:share:weiboApp","menuItem:share:facebook","menuItem:share:QZone","menuItem:copyUrl","menuItem:openWithQQBrowser","menuItem:openWithSafari","menuItem:share:email"] // 要显示的菜单项，所有menu项见附录3
        //});
        wx.onMenuShareAppMessage({
            title: '与刘嘉玲、娜扎一起领略ROSSO VALENTINO梦幻之作',
            desc: '红色SPIKE铆钉链条包，Valentino微信独家限量发售。',
            link: 'http://rossovalentino.samesamechina.com/event/',
            imgUrl: window.location.origin+'/src/images/share.jpg',
            type: '',
            dataUrl: '',
            success: function () {
                //    success
                _hmt.push(['_trackEvent', 'btn-weixin', 'share', 'success']);

            },
            cancel: function () {
            }
        });
        wx.onMenuShareTimeline({
            title: '与刘嘉玲、娜扎一起领略ROSSO VALENTINO梦幻之作',
            link: 'http://rossovalentino.samesamechina.com/event/',
            imgUrl: window.location.origin+'/src/images/share.jpg',
            success: function () {
                _hmt.push(['_trackEvent', 'btn-weixin', 'share', 'success']);
            },
            cancel: function () {

            }
        });

    })
})();
